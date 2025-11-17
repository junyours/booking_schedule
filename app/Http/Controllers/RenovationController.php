<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\TaskLog;

class RenovationController extends Controller
{
    public function index()
    {
        try {
            // Fetch only services with the category 'renovation' and status 'available', with pagination
            $services = Service::where('category', 'renovation')
                                ->where('status', 'available')
                                ->paginate(6); // Limit to 10 items per page
    
            return view('renovation.index', ['services' => $services]);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error fetching renovation services: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to fetch renovation services.');
        }
    }

    public function create()
    {
        $complexityLevels = ['very_easy', 'easy', 'medium', 'hard', 'very_hard'];
        return view('renovation.form', [
            'complexityLevels' => $complexityLevels,
            'service' => null
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'design' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'complexity' => 'required|string|in:very_easy,easy,medium,hard,very_hard',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'type' => 'required|string|in:landscaping,swimmingpool',
        ]);

        // Store the design file if provided
        $path = $request->file('design') ? $request->file('design')->store('designs', 'public') : null;

        // Create the Renovation service
        $service = Service::create([
            'name' => $request->name,
            'design' => $path,
            'complexity' => $request->complexity,
            'description' => $request->description,
            'category' => $request->category, // Use the category from the form
            'type' => $request->type // Use the type from the form
        ]);

         // Get the currently authenticated user
    $user = auth()->user(); 

    // Create a task log entry
    TaskLog::create([
        'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
        'type' => 'Renovation Service', // Type of action being logged
        'type_id' => $service->id, // ID of the created service
        'action' => 'Created Renovation service', // Description of the action
        'action_date' => now(), // Current timestamp
    ]);

        return redirect()->route('renovation')->with('success', 'Service added successfully.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $complexityLevels = ['very_easy','easy', 'medium', 'hard', 'very_hard'];
        return view('renovation.form', [
            'service' => $service,
            'complexityLevels' => $complexityLevels,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'design' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'complexity' => 'required|string|in:very_easy,easy,medium,hard,very_hard',
            'description' => 'nullable|string',
        ]);

        $service = Service::findOrFail($id);

        if ($request->hasFile('design')) {
            $path = $request->file('design')->store('designs', 'public');
            $service->design = $path;
        }

        $service->name = $request->name;
        $service->complexity = $request->complexity;
        $service->description = $request->description;

        $service->save();

        $user = auth()->user(); 

        // Create a task log entry
        TaskLog::create([
            'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
            'type' => 'Renovation Service', // Type of action being logged
            'type_id' => $service->id, // ID of the created service
            'action' => 'Updated Renovation service', // Description of the action
            'action_date' => now(), // Current timestamp
        ]);

        return redirect()->route('renovation')->with('success', 'Service updated successfully.');
    }

    public function archive($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->status = 'archive';
            $service->save();

            $user = auth()->user(); 

            // Create a task log entry
            TaskLog::create([
                'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
                'type' => 'Archive Service', // Type of action being logged
                'type_id' => $service->id, // ID of the created service
                'action' => 'Archived Renovation service', // Description of the action
                'action_date' => now(), // Current timestamp
            ]);
            return redirect()->route('renovation')->with('success', 'Service archived successfully.');
        } catch (\Exception $e) {
            \Log::error('Error archiving renovation service: ' . $e->getMessage());
            return redirect()->route('renovation')->with('error', 'An error occurred while archiving the service.');
        }
    }
}
