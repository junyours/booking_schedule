<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ProjectCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Project;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Booking;
use App\Models\TaskLog; 
use App\Models\Notification;


use Illuminate\Support\Facades\Log;



class ProjectController extends Controller
{
    public function index(Request $request, $booking_id = null)
{
    // Get the currently logged-in user
    $user = auth()->user();
    
    // Fetch the query parameters for filtering
    $statusFilter = $request->query('project_status');
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');
    
    // Initialize the query for fetching projects
    $query = Project::with(['booking', 'service']);
    
    // Check if the user has an associated booking ID
    if ($booking_id) {
        // Retrieve projects associated with the user's bookings
        $query->where('booking_id', $booking_id)
              ->whereHas('booking', function($query) use ($user) {
                  $query->where('user_id', $user->id);
              });
    } else {
        // If no booking ID is provided, return projects associated with the user
        $query->whereHas('booking', function($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }
    
    // Apply the project status filter if present
    if ($statusFilter) {
        $query->where('project_status', $statusFilter);
    }
    
    // Apply date filters if both start and end dates are the same
    if ($startDate && $endDate && $startDate == $endDate) {
        // Filter for projects created on the same day
        $query->whereDate('created_at', $startDate);
    } else {
        // If different start and end dates are provided, filter between the range
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
    
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
    }
    
    // Default sorting is latest to oldest
    $query->orderBy('created_at', 'desc');
    
    // Paginate the results
    $projects = $query->paginate(10);
    
    // Debugging: Log the retrieved projects
    \Log::info('Retrieved Projects:', $projects->toArray());
    
    // Return the view with the filtered projects and optionally the booking_id
    return view('project.index', compact('projects', 'booking_id'));
}

    

    
    
    

    


    public function create($booking_id = null)
    {
        // Define the discount options as an associative array
        $discounts = [
            0 => '0',  // 0% discount
            1 => '1',  // 10% discount
            2 => '2',  // 20% discount
            3 => '3',  // 30% discount
            4 => '4',  // 40% discount
            5 => '5',  // 50% discount
            6 => '6',  // 60% discount
            7 => '7',  // 70% discount
            8 => '8',  // 80% discount
            9 => '9',  // 90% discount
            10 => '10', // 100% discount
            12 => '12',  // 100% discount
            15 => '15'  // 100% discount
        ];

        // Return the view with the booking_id and discounts
        return view('project.adminCreate', compact('booking_id','discounts'));
    }


  

    public function hold(Request $request, $id)
    {
        // Retrieve the project by ID
        $project = Project::findOrFail($id);
        
        // Change project status to 'hold'
        $project->project_status = 'hold';
        $project->save();
    
        // Assuming the project has a booking_id column
        $booking = Booking::find($project->booking_id); // Adjust this if the column name is different
        
        // Get the currently authenticated user
        $user = auth()->user(); 
    
        // Log the task in the task_logs table
        TaskLog::create([
            'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
            'type_id' => $project->id, // Assuming type_id is related to the project ID
            'type' => 'Project', // Type of action
            'action' => 'Update project status to Hold. Project ID: ' . $project->id, // Description of action
            'action_date' => now(), // Current timestamp
        ]);
    
        // Create a notification for the user who made the booking
        if ($booking) {
            Notification::create([
                'user_id' => 1, // Hardcoded user ID as per your requirement
                'sent_to' => $booking->user_id, // The user who should receive the notification
                'title' => 'Project Update', // Corrected spelling
                'message' => 'Admin has put your project on hold. Project ID: ' . $project->id,
                'sent_at' => now(),
                'type' => 'Project', // Set type to Booking
                'type_id' => $project->id // Set type_id to the ID of the booking
            ]);
        }
    
        // Optional: Add a success message to the session
        return redirect()->back()->with('success', 'Project status updated to hold.');
    }
    

    

    public function activate(Request $request, $id)
    {
        // Retrieve the project by ID
        $project = Project::findOrFail($id);
        
        // Change project status to 'hold'
        $project->project_status = 'active';
        $project->save();
    
        // Assuming the project has a booking_id column
        $booking = Booking::find($project->booking_id); // Adjust this if the column name is different
        
        // Get the currently authenticated user
        $user = auth()->user(); 
    
        // Log the task in the task_logs table
        TaskLog::create([
            'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
            'type_id' => $project->id, // Assuming type_id is related to the project ID
            'type' => 'Project', // Type of action
            'action' => 'Update project status to Active. Project ID: ' . $project->id, // Description of action
            'action_date' => now(), // Current timestamp
        ]);
    
        // Create a notification for the user who made the booking
        if ($booking) {

            Notification::create([
                'user_id' => 1, // Hardcoded user ID as per your requirement
                'sent_to' => $booking->user_id, // The user who should receive the notification
                'title' => 'Project Update', // Corrected spelling
                'message' => 'Admin change your project to active . Project ID: ' . $project->id,
                'sent_at' => now(),
                'type' => 'Project', // Set type to Booking
                'type_id' => $project->id // Set type_id to the ID of the booking
            ]);
        }
    
        // Optional: Add a success message to the session
        return redirect()->back()->with('success', 'Project status updated to hold.');
    }

    public function cancel(Request $request, $id)
{
    // Retrieve the project by ID
    $project = Project::findOrFail($id);
    
    // Change project status to 'cancel'
    $project->project_status = 'cancel';
    $project->save();

    // Retrieve the booking associated with the project
    $booking = Booking::find($project->booking_id); // Adjust if the column name is different
    
    // Get the currently authenticated user
    $user = auth()->user(); 
    
    // Log the task in the task_logs table
    TaskLog::create([
        'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
        'type_id' => $project->id, // Assuming type_id is related to the project ID
        'type' => 'Project', // Type of action
        'action' => 'Update project status to Cancel. Project ID: ' . $project->id, // Description of action
        'action_date' => now(), // Current timestamp
    ]);

    // Create a notification for the user who made the booking
    if ($booking) {
        Notification::create([
            'user_id' => 1, // Hardcoded admin user ID, adjust as needed
            'sent_to' => $booking->user_id, // User who should receive the notification
            'title' => 'Project Update', 
            'message' => 'Admin has canceled your project. Project ID: ' . $project->id,
            'sent_at' => now(),
            'type' => 'Project', 
            'type_id' => $project->id // ID of the project
        ]);
    }

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Project status updated to cancel.');
}




     public function generateReport()
     {
         // Retrieve all projects
         $projects = Project::all(); // You can filter by criteria if necessary
 
         // Pass the projects to the view
         return view('projects.reports', compact('projects'));
     }

        // app/Http/Controllers/ProjectController.php

            public function show($id)
            {
                // Find the project by its ID
                $project = Project::findOrFail($id);

                // Pass the project to the view
                return view('project.show', compact('project'));
            }

    
      /**
     * Fetch designs based on the selected service category.
     *
     * @param string $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDesigns($type)
    {
        // Define valid categories
        $validCategories = ['landscaping', 'swimmingpool', 'maintenance', 'renovation'];
    
        // Validate service category
        if (!in_array($type, $validCategories)) {
            return response()->json(['error' => 'Invalid category'], 400);
        }
    
        // Fetch designs from services based on category and status
        $designs = Service::where('category', $type)
            ->where('status', 'available') // Only available services
            ->select('id', 'name', 'design', 'description', 'complexity') // Adjust fields based on your table
            ->get();
    
        // Add a 'type' field for renovation designs
        if ($type === 'renovation') {
            $designs->each(function ($design) {
                $design->type = 'Renovation'; // Add 'type' field
            });
        }
    
        // Ensure the design URL is correctly formatted
        $designs->each(function ($design) {
            $design->design = asset('storage/' . $design->design); // Assuming images are stored in 'public/storage'
        });
    
        return response()->json($designs);
    }
    
    public function adminIndex(Request $request)
    {
        // Fetch the query parameters for filtering
        $statusFilter = $request->query('project_status');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        // Fetch projects with associated services and bookings
        $query = Project::with(['booking', 'service']);
        
        // Apply status filter if present
        if ($statusFilter) {
            $query->where('project_status', $statusFilter);
        }
        
        // Apply date filters if present
        if ($startDate || $endDate) {
            // Default to today's date if either is missing
            $startDate = $startDate ?? now()->toDateString();
            $endDate = $endDate ?? now()->toDateString();
    
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        }
        
        // Default sorting is latest to oldest
        $query->orderBy('created_at', 'desc');
        
        // Paginate the results
        $projects = $query->paginate(5);
        
        // Pass the filters to the view
        return view('project.adminIndex', compact('projects', 'statusFilter', 'startDate', 'endDate'));
    }
    
    
    

    public function adminShow($id)
    {
        // Fetch the project by ID
        $projects = Project::with('service', 'booking', 'progress','payments')->findOrFail($id);
        
        // Return a view with the project details
        return view('project.adminShow', compact('projects')); // Pass the single project
    }
    
    
    public function view($id)
    {
        // Retrieve the project by its ID
        $projects = Project::with('service', 'booking', 'progress','payments')->findOrFail($id);
    
        // Return a view with the project details
        return view('project.view', compact('projects'));
    }
    

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
            'lot_area' => 'required|numeric|min:1',
            'cost' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date|after:today',
            'description' => 'nullable|string',
        ]);
    
        // Get the first selected service ID for service_id
        $service_id = $request->service_ids[0];
    
        // Retrieve the service and its details
        $service = Service::find($service_id);
    
        // Fetch the booking to get the user who made the booking
        $booking = Booking::find($request->booking_id);
        $user = $booking->user; // Assuming a 'user' relationship exists on Booking
    
        // Get the cost from the request
        $cost = $request->cost;
        $total_cost = $cost; // Initialize total_cost with the cost
    
        // Apply discount if available
        if ($request->discount) {
            $discountAmount = ($cost * ($request->discount / 100));
            $total_cost -= $discountAmount; // Subtract discount from total_cost
        }
    
        try {
            // Log the project creation details
            Log::info('Creating project', [
                'booking_id' => $request->booking_id,
                'service_id' => $service_id,
                'lot_area' => $request->lot_area,
                'discount' => $request->discount,
                'total_cost' => $total_cost,
                'start_date' => $request->start_date,
            ]);
    
            // Create a new project record with project_status set to 'pending'
            $project = Project::create([
                'booking_id' => $request->booking_id,
                'service_id' => $service_id,
                'service_ids' => json_encode($request->service_ids),
                'lot_area' => $request->lot_area,
                'cost' => $cost,
                'total_cost' => $total_cost,
                'description' => $request->description,
                'discount' => $request->discount,
                'start_date' => $request->start_date,
                'project_status' => 'pending',
            ]);
    
            // Update the booking status to completed
            $booking->update(['booking_status' => 'visited']);
            $user = auth()->user(); // Get the currently authenticated user

            // Log the task in the task_logs table
            TaskLog::create([
                'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
                'type_id' => $project->id, // Assuming type_id is related to the project ID
                'type' => 'Project', // Type of action
                'action' => 'Created a new project for booking ID: ' . $request->booking_id,
                'action_date' => now(), // Current timestamp
            ]);
    
            // Send the email notification to the booking email
            try {
                Mail::to($booking->email)->send(new ProjectCreatedMail($project));
            } catch (\Exception $emailException) {
                Log::error('Error sending email: ' . $emailException->getMessage());
            }

                // Create a notification for the user who made the booking
            Notification::create([
                'user_id' => 1, // User who made the booking
                'sent_to' => $booking->user_id, // You can adjust this according to your logic
                'title' => 'Project Created',
                'message' => 'A new project has been created for your booking ID: ' . $booking->id,
                'sent_at' => now(),
                'type' => 'Project', // Set type to Booking
                'type_id' => $project->id // Set type_id to the ID of the booking
            ]);
    
            return redirect()->route('projects.adminIndex')->with('success', 'Project added successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating project: ' . $e->getMessage());
            return back()->with('error', 'There was an error creating the project. Please try again.');
        }
    }
    
    
    
    
    
    
    public function edit($id)
    {
        // Retrieve the project by ID
        $project = Project::findOrFail($id); // Will throw a 404 if not found
    
        // Decode the service_ids if it's not already an array
        $serviceIds = json_decode($project->service_ids, true) ?? []; // Default to an empty array if it's not valid
    
        // Fetch the service names based on service_ids
        $services = Service::whereIn('id', $serviceIds)->get();
    
        // If you need the booking_id
        $booking_id = $project->booking_id;
    
        // Define the discount options as an associative array
        $discounts = [
            0 => '0',
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
            12 => '12',
            15 => '15'
        ];
    
        // Return the edit view with the project, booking ID, discounts, services, and start_date
        return view('project.adminEdit', compact('project', 'booking_id', 'discounts', 'services'));
    }
    

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'service_ids' => 'required|array', // Accept an array of service IDs
            'service_ids.*' => 'exists:services,id', // Ensure each service ID exists in the services table
            'lot_area' => 'required|numeric|min:1',
            'cost' => 'required|numeric|min:0', // Accept a cost value
            'discount' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date|after:today', // Validate start date
            'description' => 'nullable|string',
        ]);
    
        // Retrieve the project to update
        $project = Project::findOrFail($id);

          // Fetch the booking to get the user who made the booking
          $booking = Booking::find($request->booking_id);
          $user = $booking->user; // Assuming a 'user' relationship exists on Booking
    
        // Get the first selected service ID for service_id
        $service_id = $request->service_ids[0];
    
        // Get the cost from the request
        $cost = $request->cost; 
        $total_cost = $cost; // Initialize total_cost with the cost
    
        // Apply discount if available
        if ($request->discount) {
            $discountAmount = ($cost * ($request->discount / 100));
            $total_cost -= $discountAmount; // Subtract discount from total_cost
        }
    
        try {
            // Log the project update details
            Log::info('Updating project', [
                'project_id' => $project->id,
                'service_id' => $service_id,
                'lot_area' => $request->lot_area,
                'discount' => $request->discount,
                'total_cost' => $total_cost,
                'start_date' => $request->start_date,
            ]);
    
            // Update the project record
            $project->update([
                'service_id' => $service_id, // Use the first selected service ID for the main service
                'service_ids' => !empty($request->service_ids) ? json_encode($request->service_ids) : $project->service_ids, // Update only if new services are selected
                'lot_area' => $request->lot_area,
                'cost' => $cost, // Store the initial cost
                'total_cost' => $total_cost, // Store the total cost calculated
                'description' => $request->description ?? $project->description, // Keep existing description if not provided
                'discount' => $request->discount ?? $project->discount, // Keep existing discount if not provided
                'start_date' => $request->start_date, // Ensure start date is updated
                'project_status' => $project->project_status, // Keep the existing status if not changing
            ]);
    
            // Add a record in the task logs
            TaskLog::create([
                'user_id' => auth()->id(), // Get the current user ID
                'type_id' => $project->id, // Assuming you want to link this log to the project
                'type' => 'Project', // You can define types as needed
                'action' => 'Updated project',
                'action_date' => now(), // Set action date to current timestamp
            ]);
            
                     // Create a notification for the user who made the booking
                     Notification::create([
                        'user_id' => 1, // User who made the booking
                        'sent_to' => $booking->user_id, // You can adjust this according to your logic
                        'title' => 'Project Updated',
                        'message' => 'project has been updated for your booking ID: ' . $booking->id,
                        'sent_at' => now(),
                        'type' => 'Project', // Set type to Booking
                        'type_id' => $project->id // Set type_id to the ID of the booking
                    ]);
            
    
            return redirect()->route('projects.adminIndex')->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating project: ' . $e->getMessage());
            return back()->with('error', 'There was an error updating the project. Please try again.');
        }
    }
    
    
    
}
