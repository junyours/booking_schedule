<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\TaskLog;

use Illuminate\Support\Facades\Storage;

class LandscapeController extends Controller
{
    public function index()
    {
        try {
            $services = Service::where('category', 'landscaping')
                                ->where('status', 'available')
                                ->paginate(6); // Limit to 10 items per page
    
            return view('landscape.index', ['services' => $services]);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error fetching landscape services: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to fetch landscape services.');
        }
    }
    
    

    public function countLandscapeServices()
    {
        try {
            return Service::where('status', 'available')->count();
        } catch (\Exception $e) {
            Log::error('Error counting landscape services: ' . $e->getMessage());
            return 0;
        }
    }
    
    

    public function create()
    {
        $landscape_id = 1; 
        $complexityLevels = ['very_easy','easy', 'medium', 'hard', 'very_hard'];
        return view('landscape.form', ['landscape_id' => $landscape_id]);
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
        'category' => 'landscaping', // Ensure the category is correctly set
    ]);

        $user = auth()->user(); // Get the currently authenticated user
    TaskLog::create([
        'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
        'type' => 'Landscaping Service', // Adjust based on your task log type definitions
        'type_id' => $service->id, // ID of the service just created
        'action' => 'Created a new landscaping service',
        'action_date' => now(), // Current timestamp
    ]);

    return redirect()->route('landscape')->with('success', 'Service added successfully.');
}
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $complexityLevels = ['very_easy','easy', 'medium', 'hard', 'very_hard'];
        return view('landscape.form', [
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
    
        $user = auth()->user(); // Get the currently authenticated user
        TaskLog::create([
            'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
            'type' => 'Landscaping Service', // Adjust based on your task log type definitions
            'type_id' => $service->id, // ID of the service that was updated
            'action' => 'Updated landscaping service',
            'action_date' => now(), // Current timestamp
        ]);
    
        return redirect()->route('landscape')->with('success', 'Service updated successfully.');
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
            'action' => 'Archived landscaping service',
            'action_date' => now(), // Current timestamp
        ]);

        return redirect()->route('landscape')->with('success', 'Service archived successfully.');
    } catch (\Exception $e) {
        \Log::error('Error archiving service: ' . $e->getMessage());
        return redirect()->route('landscape')->with('error', 'An error occurred while archiving the service.');
    }
}

    
}
