<?php

// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Booking;
use App\Models\TaskLog;
use App\Models\Notification;
use App\Mail\PaymentNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; // Import Auth to get the logged-in user
use Illuminate\Http\Request;

class PaymentController extends Controller
{  

    public function index(Request $request)
{
    $user = Auth::user();
    
    // Initialize the query for payments, filtering by the authenticated user's bookings and projects
    $query = Payment::whereHas('project.booking', function($query) use ($user) {
        $query->where('user_id', $user->id);
    });

    // Apply the same day filter if both start_date and end_date are the same
    if ($request->has('start_date') && $request->has('end_date') && $request->start_date == $request->end_date) {
        // Filter for payments created on the same day
        $query->whereDate('created_at', $request->start_date);
    } else {
        // Apply start date filter if present
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('created_at', '>=', $request->start_date);
        }

        // Apply end date filter if present
        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('created_at', '<=', $request->end_date);
        }
    }

    // Default sorting is latest to oldest
    $query->orderBy('created_at', 'desc');
    
    // Paginate the filtered payments
    $payments = $query->paginate(5); // Adjust pagination as needed
    
    // Return the view for payments, passing the filtered payments data
    return view('payment.index', compact('payments'));
}

    

    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('payment.initial', compact('project'));
    }


    public function payment($projectId)
    {
        // Fetch the project details using the $projectId
        $project = Project::findOrFail($projectId);

        // You can pass the project data to the payment view
        return view('payment.payment', compact('project'));
    }

    public function adminIndex(Request $request)
{
    // Start building the query
    $query = Payment::query();

    // Apply date filters if present
    if ($request->filled('start_date') || $request->filled('end_date')) {
        $startDate = $request->input('start_date', now()->toDateString()); // Default to today's date if not provided
        $endDate = $request->input('end_date', now()->toDateString());     // Default to today's date if not provided

        $query->whereDate('created_at', '>=', $startDate)
              ->whereDate('created_at', '<=', $endDate);
    }

    // Default sorting is latest to oldest
    $query->orderBy('created_at', 'desc'); 

    // Paginate the results
    $payments = $query->paginate(5); // Adjust items per page as needed

    // Return the view with the filtered payments and date filters
    return view('payment.adminIndex', compact('payments'));
}

    
    


    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'payment_type' => 'required|in:initial,midterm,final',
            'payment_method' => 'required|in:cash,gcash,bank_transfer',
            'amount' => 'required|numeric|min:0',
            'payment_image' => 'nullable|image|max:2048', // Optional payment proof, max size 2MB
        ]);
    
        // Handle the payment proof upload if it exists
        $paymentImagePath = null;
        if ($request->hasFile('payment_image')) {
            $paymentImagePath = $request->file('payment_image')->store('payments', 'public');
        }
    
        // Find the project and associated booking
        $project = Project::with('booking.user')->findOrFail($request->project_id); // Eager load booking and user
    
        // Calculate total payments for the project
        $totalPayments = Payment::where('project_id', $project->id)->sum('amount');
    
        // Calculate the expected total
        $newTotalPaid = $totalPayments + $request->amount;
    
        $project->total_paid = $newTotalPaid; // Update the total paid
        $project->project_status = 'active'; // Set project status to active
        $project->save(); // Save the updated project
    
        // Create a new payment record
        $payment = Payment::create([
            'project_id' => $project->id,
            'payment_type' => $request->payment_type,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'payment_image' => $paymentImagePath,
            'remarks' => $request->remarks,
        ]);
    
        // Retrieve the currently authenticated user
        $user = auth()->user(); // Get the currently authenticated user
    
        // Create a new task log entry for the payment
        TaskLog::create([
            'user_id' => $user ? $user->id : null, // User ID or null if not authenticated
            'type' => 'Payment',
            'type_id' => $payment->id, // Reference to the payment ID
            'action' => 'Payment of ' . number_format($request->amount, 2) . ', submitted for project ID: ' . $request->project_id,
            'action_date' => now(), // Current date and time
        ]);
    
        // Create a notification for the user who made the payment
        if ($project->booking && $project->booking->user) { // Check if booking and user exist
            Notification::create([
                'user_id' => $user->id, // User who made the payment
                'sent_to' => $project->booking->user->id, // Access user_id through the booking's user relationship
                'title' => 'Payment Submitted',
                'message' => 'A payment of PHP ' . number_format($request->amount, 2) . ' has been submitted for project ID: ' . $project->id,
                'sent_at' => now(),
                'type' => 'Payment', // Set type to Booking
                'type_id' => $payment->id // Set type_id to the ID of the booking
            ]);
        }
        Mail::to($project->booking->user->email)->send(new PaymentNotification($payment, $project));
    
        return redirect()->route('project.adminIndex')->with('success', 'Payment successfully submitted and is pending approval.');
    }
    
    
    

    
    
    public function edit($id)
    {
        // Retrieve the payment by its ID
        $payment = Payment::findOrFail($id);
    
        // Get the associated project using the relationship
        $project = $payment->project; // Assuming you have a 'project' relationship in Payment model
    
        // Pass both payment and project data to the view
        return view('payment.adminEdit', compact('payment', 'project'));
    }
    
    public function update(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'payment_method' => 'required|string',
        'amount' => 'required|numeric|min:0',
    ]);

    // Find the payment record
    $payment = Payment::findOrFail($id);

    // Update payment details
    $payment->payment_method = $request->input('payment_method');
    $payment->amount = $request->input('amount');
    $payment->save();

    // Update total_paid in the project
    $project = Project::findOrFail($payment->project_id);
    $totalPayments = Payment::where('project_id', $project->id)
        ->sum('amount');

    // Update the project's total_paid
    $project->total_paid = $totalPayments;
    $project->project_status = 'active';
    $project->save();

    return redirect()->route('admin.payments.index')->with('success', 'Payment approved and total paid updated successfully.');
}
    
 

public function showPaymentForm($id)
{
    $project = Project::findOrFail($id);
    return view('your.view.name', compact('project'));
}


public function show($id)
{
    $payment = Payment::findOrFail($id); // Fetch the payment record by ID

    // Decode payment_images if it's a JSON string
    $paymentImage = !empty($payment->payment_image) ? json_decode($payment->payment_image, true) : []; // Ensure it's decoded or set to an empty array

    return view('payment.show', [
        'payment' => $payment,
        'totalPaid' => $payment->amount, // Assuming this is what you want to show
        'paymentImage' => $paymentImage // Pass paymentImages to the view
    ]);
}



public function adminshow($id)
{
    $payment = Payment::findOrFail($id); // Fetch the payment record by ID

    // Fetch total paid amount for the specific project

    return view('payment.adminShow', compact('payment')); // Pass payment and totalPaid to the view
}

    

public function viewPayments($projectId)
{
    $project = Project::findOrFail($projectId); // Fetch the project
    $payments = Payment::where('project_id', $projectId)->get(); // Fetch payments for the project

    // Calculate total paid amount for the project
    $totalPaid = Payment::where('project_id', $projectId)->sum('amount'); // Sum the 'amount' for the project

    // Pass total paid to the view correctly
    return view('payment.view', compact('project', 'payments', 'totalPaid')); // Pass the correct variable name
}


public function adminviewPayments($projectId)
{
    $project = Project::findOrFail($projectId); // Fetch the project
    $payments = Payment::where('project_id', $projectId)->get(); // Fetch payments for the project

    // Calculate total paid amount for the project
    $totalPaid = Payment::where('project_id', $projectId)->sum('amount'); // Sum the 'amount' for the project

    // Pass total paid to the view correctly
    return view('payment.view', compact('project', 'payments', 'totalPaid')); // Pass the correct variable name
}





}
