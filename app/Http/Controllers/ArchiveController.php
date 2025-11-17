<?php

namespace App\Http\Controllers;

use App\Models\Renovation;
use App\Models\Landscape;
use App\Models\SwimmingPool;
use App\Models\Service;
use App\Models\TaskLog;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Default to 'all' if no filter is provided
            $filter = $request->input('filter', 'all');
    
            // Fetch archived services based on the filter
            if ($filter === 'all') {
                $archivedServices = Service::where('status', 'archive')->paginate(6); // Adjust the number as needed
            } else {
                $archivedServices = Service::where('status', 'archive')
                    ->where('category', $filter)
                    ->paginate(6); // Adjust the number as needed
            }
    
            return view('archive.index', [
                'services' => $archivedServices,
                'filter' => $filter, // Pass the filter to the view
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching archived services: ' . $e->getMessage());
            return back()->with('error', 'Failed to fetch archived services.');
        }
    }
    
    

    public function restore($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->status = 'available'; // Set status to 'available'
            $service->save();

            $user = auth()->user(); 

            // Create a task log entry
            TaskLog::create([
                'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
                'type' => 'Archive Service', // Type of action being logged
                'type_id' => $service->id, // ID of the created service
                'action' => 'Package service made Available', // Description of the action
                'action_date' => now(), // Current timestamp
            ]);

            return redirect()->route('archive.index')->with('success', 'Service restored successfully.');
        } catch (\Exception $e) {
            \Log::error('Error restoring service: ' . $e->getMessage());
            return back()->with('error', 'Failed to restore service.');
        }
    }

    // Method to delete archived service
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete(); // Permanently delete the service

            $user = auth()->user(); // Get the currently authenticated user
            TaskLog::create([
                'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
                'type' => 'Archive Service', // Adjust based on your task log type definitions
                'type_id' => $service->id, // ID of the archived service
                'action' => 'Deleted landscaping service',
                'action_date' => now(), // Current timestamp
            ]);
            return redirect()->route('archive.index')->with('success', 'Service deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting service: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete service.');
        }
    }
}
