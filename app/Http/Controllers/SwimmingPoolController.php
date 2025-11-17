<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\TaskLog;
use Illuminate\Support\Facades\Storage;

class SwimmingPoolController extends Controller
{
    public function index()
    {
        try {
            // Fetch only services with the category 'swimmingpool' and status 'available', with pagination
            $services = Service::where('category', 'swimmingpool')
                                ->where('status', 'available')
                                ->paginate(6); 
    
            return view('swimmingpool.index', ['services' => $services]);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error fetching swimming pool services: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to fetch swimming pool services.');
        }
    }
    
    

    public function create()
    {
        $swimmingpool_id = 2; 
        $complexityLevels = ['very_easy','easy', 'medium', 'hard', 'very_hard'];
        return view('swimmingpool.form', ['swimmingpool_id' => $swimmingpool_id]);
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'design' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        'complexity' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    // Store the design file
    $path = $request->file('design')->store('designs', 'public');

    // Create the SwimmingPool service
    $service = Service::create([
        'name' => $request->name,
        'design' => $path,
        'complexity' => $request->complexity,
        'description' => $request->description,
        'category' => 'swimmingpool', // Ensure the category is correctly set
    ]);

    // Get the currently authenticated user
    $user = auth()->user(); 

    // Create a task log entry
    TaskLog::create([
        'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
        'type' => 'Swimmingpool Service', // Type of action being logged
        'type_id' => $service->id, // ID of the created service
        'action' => 'Created swimming pool service', // Description of the action
        'action_date' => now(), // Current timestamp
    ]);

    return redirect()->route('swimmingpool')->with('success', 'Service added successfully.');
}

    
    
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $complexityLevels = ['very_easy','easy', 'medium', 'hard', 'very_hard'];
        return view('swimmingpool.form', [
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
            'type' => 'Swimmingpool Service', // Type of action being logged
            'type_id' => $service->id, // ID of the created service
            'action' => 'Updated swimming pool service', // Description of the action
            'action_date' => now(), // Current timestamp
        ]);
    

        return redirect()->route('swimmingpool')->with('success', 'Service updated successfully.');
    }
    
    
    public function archive($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->status = 'archive';
            $service->save();

            $user = auth()->user(); // Get the currently authenticated user
            TaskLog::create([
                'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
                'type' => 'Archive Service', // Adjust based on your task log type definitions
                'type_id' => $service->id, // ID of the archived service
                'action' => 'Archived Swimmingpool service',
                'action_date' => now(), // Current timestamp
            ]);
            return redirect()->route('swimmingpool')->with('success', 'Service archived successfully.');
        } catch (\Exception $e) {
            // \Log::error('Error archiving service: ' . $e->getMessage());
            return redirect()->route('swimmingpool')->with('error', 'An error occurred while archiving the service.');
        }
    }
    
  
}
