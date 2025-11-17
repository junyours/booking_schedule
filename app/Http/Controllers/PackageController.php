<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service; // Make sure to import the correct Package model
use App\Models\TaskLog;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index()
    {
        try {
            $service = Service::where('category', 'package')
                                ->where('status', 'available')
                                ->paginate(6); // Limit to 10 items per page


    
            return view('package.index', ['packages' => $service]);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error fetching packages: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to fetch package packages.');
        }
    }
    
    public function countPackages()
    {
        try {
            return Service::where('status', 'available')->count();
        } catch (\Exception $e) {
            \Log::error('Error counting packages: ' . $e->getMessage());
            return 0;
        }
    }
    
    public function create()
    {
        $package_id = 1; 
        $complexityLevels = ['very_easy','easy', 'medium', 'hard', 'very_hard'];
        $service = null; // Ensure this is defined
        return view('package.form', ['package_id' => $package_id, 'complexityLevels' => $complexityLevels, 'service' => $service]);
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
    
        // Create the Package
        $service = Service::create([
            'name' => $request->name,
            'design' => $path,
            'complexity' => $request->complexity,
            'description' => $request->description,
            'category' => 'package', // Ensure the category is correctly set
        ]);

               // Get the currently authenticated user
    $user = auth()->user(); 

    // Create a task log entry
    TaskLog::create([
        'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
        'type' => 'Package Service', // Type of action being logged
        'type_id' => $service->id, // ID of the created service
        'action' => 'Created Package service', // Description of the action
        'action_date' => now(), // Current timestamp
    ]);
    
        return redirect()->route('package')->with('success', 'Package added successfully.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $complexityLevels = ['very_easy','easy', 'medium', 'hard', 'very_hard'];
        return view('package.form', [
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
        'type' => 'Package Service', // Type of action being logged
        'type_id' => $service->id, // ID of the created service
        'action' => 'Updated Package service', // Description of the action
        'action_date' => now(), // Current timestamp
    ]);

        return redirect()->route('package')->with('success', 'Package updated successfully.');
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
                'action' => 'Archived Package service', // Description of the action
                'action_date' => now(), // Current timestamp
            ]);

            return redirect()->route('package')->with('success', 'Package archived successfully.');
        } catch (\Exception $e) {
            \Log::error('Error archiving package: ' . $e->getMessage());
            return redirect()->route('package')->with('error', 'An error occurred while archiving the package.');
        }
    }
}
