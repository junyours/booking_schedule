<?php

namespace App\Http\Controllers;
use App\Models\Project; //use Illuminate\Http\Request;
use App\Models\Progress; // Ensure you have a Progress model
use Illuminate\Http\Request;
use App\Models\TaskLog; // Import the TaskLog model
use App\Models\Notification;
use App\Mail\ProjectProgressUpdatedMail;
use Illuminate\Support\Facades\Mail;


class ProgressController extends Controller
{
    public function index($projectId)
    {
        // Fetch the project by ID
        $project = Project::with('service')->findOrFail($projectId);
        
        // Fetch the progress related to the project, ordered from latest to oldest
        $progress = Progress::where('project_id', $project->id)
                            ->orderBy('created_at', 'desc') // Order by the creation date in descending order
                            ->paginate(5);

        // Fetch the latest progress entry separately
        $latestProgress = Progress::where('project_id', $project->id)
                                   ->orderBy('created_at', 'desc')
                                   ->first(); // This gets the latest progress entry

        // Return the view with the project, its progress, and latest progress
        return view('progress.index', compact('project', 'progress', 'latestProgress'));
    }



    public function view($projectId)
    {
         // Fetch the project by ID
         $project = Project::with('service')->findOrFail($projectId);
        
         // Fetch the progress related to the project, ordered from latest to oldest
         $progress = Progress::where('project_id', $project->id)
                             ->orderBy('created_at', 'desc') // Order by the creation date in descending order
                             ->paginate(5);
 
         // Fetch the latest progress entry separately
         $latestProgress = Progress::where('project_id', $project->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first(); // This gets the latest progress entry
 
         // Return the view with the project, its progress, and latest progress
        return view('progress.view', compact('project', 'progress', 'latestProgress'));
    }

    


    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'phase' => 'required|in:phase_one,phase_two,phase_three',
            'phase_progress' => 'required|in:0,10,20,30,40,50,60,70,80,90,100',
            'image' => 'nullable|image|max:2048', // Optional image
            'remarks' => 'nullable|string', // Make remarks optional
        ]);
    
        // Handle file upload for the image if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('project_images', 'public');
        }
    
        // Set default value for remarks if empty
        $remarks = $request->remarks ?: 'No remarks'; // Use 'No remarks' if remarks is empty
    
        // Create new tracking entry
        $progress = Progress::create([
            'project_id' => $request->project_id,
            'phase' => $request->phase,
            'phase_progress' => $request->phase_progress,
            'image' => $imagePath,
            'remarks' => $remarks, // Save remarks (default or provided)
        ]);
    
        // Update project status if phase is phase_three and progress is 100
        if ($request->phase === 'phase_three' && $request->phase_progress == 100) {
            $project = Project::findOrFail($request->project_id);
            $project->update(['project_status' => 'finish']); // Update project status
        }
    
        // Log the progress in the task log
        TaskLog::create([
            'user_id' => auth()->id(), // Capture the ID of the authenticated user
            'type' => 'Progress', // Adjust based on your task log types
            'type_id' => $request->project_id, // Set type_id to the project_id instead of progress ID
            'action' => 'Project progress updated for project ID: ' . $request->project_id . ' - ' . $request->phase . ' - ' . $request->phase_progress . '%',
            'action_date' => now(), // Use the current date and time
            'details' => $remarks, // Optionally include remarks in details
        ]);
    
        // Find the project to create a notification
        $project = Project::with('booking.user')->findOrFail($request->project_id); // Eager load booking and user
    
        // Create a notification for the user associated with the project
        if ($project->booking && $project->booking->user) { // Check if booking and user exist
            Notification::create([
                'user_id' => auth()->id(), // User who made the progress update
                'sent_to' => $project->booking->user->id, // Access user_id through the booking's user relationship
                'title' => 'Project Progress Updated',
                'message' => 'The progress for project ID: ' . $project->id . ' has been updated to ' . $request->phase . ' ' . $request->phase_progress . '%',
                'sent_at' => now(),
                'type' => 'Progress', // Set type to Progress
                'type_id' => $project->id // Set type_id to the ID of the project
            ]);
             // Send email notification
        Mail::to($project->booking->user->email)->send(new ProjectProgressUpdatedMail(
            $project,
            $request->phase,
            $request->phase_progress,
            $remarks
        ));
        }
    
        return response()->json(['success' => true, 'message' => 'Project progress stored successfully!']);
    }
    
    
    
    
    
}
