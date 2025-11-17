<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Mail\BookingSuccessMail;
use App\Mail\BookingConfirmed;
use App\Mail\BookingDeclined;
use App\Models\User;
use App\Models\TaskLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; // Make sure to include this at the top




class BookingController extends Controller
{
    public function index(Request $request)
    {
        // Initialize the query for fetching bookings
        $query = Booking::where('user_id', Auth::id()); // Assuming user-specific bookings; adjust if needed
    
        // Apply booking status filter if provided
        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->input('booking_status'));
        }
    
        // Apply date filters for site_visit_date if provided
        if ($request->filled('start_date') || $request->filled('end_date')) {
            // Default to today's date if no date is provided
            $startDate = $request->input('start_date', now()->toDateString());
            $endDate = $request->input('end_date', now()->toDateString());
    
            // Apply the date filters to the query for site_visit_date
            $query->whereDate('site_visit_date', '>=', $startDate)
                  ->whereDate('site_visit_date', '<=', $endDate);
        }
    
        // Order by site_visit_date (latest first)
        $query->orderBy('site_visit_date', 'desc');
    
        // Paginate the filtered bookings
        $bookings = $query->paginate(10); // Adjust items per page as needed
    
        // Return the view with the filtered bookings
        return view('booking.index', compact('bookings'));
    }
    
    
    
    
    
    
    public function adminBooking(Request $request)
{
    $query = Booking::query();
    $today = now()->toDateString(); // Default to today's date
    
    // Apply booking status filter if provided
    if ($request->filled('booking_status')) {
        $query->where('booking_status', $request->input('booking_status'));
    }
    
    // Apply site_visit_date date filters if provided
    if ($request->filled('start_date') || $request->filled('end_date')) {
        $startDate = $request->input('start_date', $today); // Default to today if not set
        $endDate = $request->input('end_date', $today);     // Default to today if not set

        // Ensure that both dates are applied only when provided
        $query->whereBetween('site_visit_date', [$startDate, $endDate]);
    }

    // Fetch filtered bookings, ordered by site_visit_date from latest to oldest
    $bookings = $query->orderBy('created_at', 'desc') // Order by descending date
                      ->paginate(8); // Adjust items per page as needed

    // Pass the bookings data and filters to the view
    return view('booking.adminBooking', compact('bookings'));
}

    
    

        
    
    
    
    

    public function create()
    {
        $cities = [
        'NCR' => [ // NCR
            ['id' => 'Manila', 'name' => 'Manila'],
            ['id' => 'Quezon City', 'name' => 'Quezon City'],
            ['id' => 'Caloocan', 'name' => 'Caloocan'],
            ['id' => 'Makati', 'name' => 'Makati'],
            ['id' => 'Taguig', 'name' => 'Taguig'],
            ['id' => 'Pasig', 'name' => 'Pasig'],
            ['id' => 'Malabon', 'name' => 'Malabon'],
            ['id' => 'Marikina', 'name' => 'Marikina'],
            ['id' => 'Navotas', 'name' => 'Navotas'],
            ['id' => 'Valenzuela', 'name' => 'Valenzuela'],
            ['id' => 'San Juan', 'name' => 'San Juan'],
            ['id' => 'Pateros', 'name' => 'Pateros'],
        ],
        'CAR' => [ // CAR
            ['id' => 'Baguio City', 'name' => 'Baguio City'],
            ['id' => 'Abra', 'name' => 'Abra'],
            ['id' => 'Apayao', 'name' => 'Apayao'],
            ['id' => 'Benguet', 'name' => 'Benguet'],
            ['id' => 'Ifugao', 'name' => 'Ifugao'],
            ['id' => 'Kalinga', 'name' => 'Kalinga'],
            ['id' => 'Mountain Province', 'name' => 'Mountain Province'],
        ],
        'Ilocos Region' => [ // Ilocos Region
            ['id' => 'Vigan', 'name' => 'Vigan'],
            ['id' => 'Laoag', 'name' => 'Laoag'],
            ['id' => 'Dagupan', 'name' => 'Dagupan'],
            ['id' => 'San Fernando', 'name' => 'San Fernando'],
            ['id' => 'Alaminos', 'name' => 'Alaminos'],
            ['id' => 'Urdaneta', 'name' => 'Urdaneta'],
        ],
        'Cagayan Valley' => [ // Cagayan Valley
            ['id' => 'Tuguegarao', 'name' => 'Tuguegarao'],
            ['id' => 'Ilagan', 'name' => 'Ilagan'],
            ['id' => 'Cauayan', 'name' => 'Cauayan'],
            ['id' => 'Santiago', 'name' => 'Santiago'],
            ['id' => 'Aparri', 'name' => 'Aparri'],
        ],
        'Central Luzon' => [ // Central Luzon
            ['id' => 'San Fernando', 'name' => 'San Fernando'],
            ['id' => 'Angeles', 'name' => 'Angeles'],
            ['id' => 'Olongapo', 'name' => 'Olongapo'],
            ['id' => 'Tarlac', 'name' => 'Tarlac'],
            ['id' => 'Pampanga', 'name' => 'Pampanga'],
            ['id' => 'Bulacan', 'name' => 'Bulacan'],
            ['id' => 'Zambales', 'name' => 'Zambales'],
        ],
        'CALABARZON' => [ // CALABARZON
            ['id' => 'Cavite', 'name' => 'Cavite'],
            ['id' => 'Batangas', 'name' => 'Batangas'],
            ['id' => 'Laguna', 'name' => 'Laguna'],
            ['id' => 'Rizal', 'name' => 'Rizal'],
            ['id' => 'Quezon', 'name' => 'Quezon'],
        ],
        'MIMAROPA' => [ // MIMAROPA
            ['id' => 'Calapan', 'name' => 'Calapan'],
            ['id' => 'Romblon', 'name' => 'Romblon'],
            ['id' => 'Puerto Princesa', 'name' => 'Puerto Princesa'],
            ['id' => 'Marinduque', 'name' => 'Marinduque'],
            ['id' => 'Occidental Mindoro', 'name' => 'Occidental Mindoro'],
            ['id' => 'Oriental Mindoro', 'name' => 'Oriental Mindoro'],
        ],
        'Bicol Region' => [ // Bicol Region
            ['id' => 'Legazpi', 'name' => 'Legazpi'],
            ['id' => 'Naga', 'name' => 'Naga'],
            ['id' => 'Iriga', 'name' => 'Iriga'],
            ['id' => 'Sorsogon', 'name' => 'Sorsogon'],
            ['id' => 'Catanduanes', 'name' => 'Catanduanes'],
        ],
        'Western Visayas' => [ // Western Visayas
            ['id' => 'Iloilo', 'name' => 'Iloilo'],
            ['id' => 'Bacolod', 'name' => 'Bacolod'],
            ['id' => 'Roxas', 'name' => 'Roxas'],
            ['id' => 'Kabankalan', 'name' => 'Kabankalan'],
            ['id' => 'San Carlos', 'name' => 'San Carlos'],
        ],
        'Central Visayas' => [ // Central Visayas
            ['id' => 'Cebu City', 'name' => 'Cebu City'],
            ['id' => 'Tagbilaran', 'name' => 'Tagbilaran'],
            ['id' => 'Dumaguete', 'name' => 'Dumaguete'],
            ['id' => 'Mandaue', 'name' => 'Mandaue'],
            ['id' => 'Lapu-Lapu', 'name' => 'Lapu-Lapu'],
        ],
        'Eastern Visayas' => [ // Eastern Visayas
            ['id' => 'Tacloban', 'name' => 'Tacloban'],
            ['id' => 'Ormoc', 'name' => 'Ormoc'],
            ['id' => 'Calbayog', 'name' => 'Calbayog'],
            ['id' => 'Catbalogan', 'name' => 'Catbalogan'],
            ['id' => 'Borongan', 'name' => 'Borongan'],
        ],
        'Zamboanga Peninsula' => [ // Zamboanga Peninsula
            ['id' => 'Zamboanga City', 'name' => 'Zamboanga City'],
            ['id' => 'Dipolog', 'name' => 'Dipolog'],
            ['id' => 'Dapitan', 'name' => 'Dapitan'],
            ['id' => 'Pagadian', 'name' => 'Pagadian'],
            ['id' => 'Dipolog', 'name' => 'Dipolog'],
        ],
        'Northern Mindanao' => [ // Northern Mindanao
            ['id' => 'Cagayan de Oro', 'name' => 'Cagayan de Oro'],
            ['id' => 'Bukidnon', 'name' => 'Bukidnon'],
            ['id' => 'Misamis Oriental', 'name' => 'Misamis Oriental'],
            ['id' => 'Misamis Occidental', 'name' => 'Misamis Occidental'],
        ],
        'Davao Region' => [ // Davao Region
            ['id' => 'Davao City', 'name' => 'Davao City'],
            ['id' => 'Tagum', 'name' => 'Tagum'],
            ['id' => 'Digos', 'name' => 'Digos'],
            ['id' => 'Panabo', 'name' => 'Panabo'],
            ['id' => 'Samal', 'name' => 'Samal'],
        ],
        'SOCCSKSARGEN' => [ // SOCCSKSARGEN
            ['id' => 'General Santos', 'name' => 'General Santos'],
            ['id' => 'Koronadal', 'name' => 'Koronadal'],
            ['id' => 'Tacurong', 'name' => 'Tacurong'],
            ['id' => 'Kidapawan', 'name' => 'Kidapawan'],
            ['id' => 'Cotabato', 'name' => 'Cotabato'],
        ],
        'Caraga' => [ // Caraga
            ['id' => 'Butuan', 'name' => 'Butuan'],
            ['id' => 'Surigao City', 'name' => 'Surigao City'],
            ['id' => 'Bislig', 'name' => 'Bislig'],
            ['id' => 'Tandag', 'name' => 'Tandag'],
            ['id' => 'Cabadbaran', 'name' => 'Cabadbaran'],
        ],
        'BARMM' => [ // BARMM
            ['id' => 'Cotabato City', 'name' => 'Cotabato City'],
            ['id' => 'Marawi City', 'name' => 'Marawi City'],
            ['id' => 'Lamitan', 'name' => 'Lamitan'],
            ['id' => 'Lanao del Sur', 'name' => 'Lanao del Sur'],
            ['id' => 'Lanao del Norte', 'name' => 'Lanao del Norte'],
            ['id' => 'Basilan', 'name' => 'Basilan'],
            ['id' => 'Sulu', 'name' => 'Sulu'],
            ['id' => 'Tawi-Tawi', 'name' => 'Tawi-Tawi'],
        ],
    ];


    return view('booking.form', compact('cities'));    }
  


    public function adminShow($id)
    {
        $booking = Booking::findOrFail($id);
        return view('booking.adminShow', compact('booking'));
    }
    

    
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'contact' => 'required|string',
        'email' => 'required|string|email',
        'site_visit_date' => 'required|date',
        'user_id' => 'required|exists:users,id',
        'address' => 'required|string',
        'province' => 'required|string',
        'city' => 'required|string',
    ]);

    // Create a new booking and include user_id
    $booking = Booking::create([
        'name' => $request->name,
        'contact' => $request->contact,
        'email' => $request->email,
        'site_visit_date' => $request->site_visit_date,
        'user_id' => $request->user_id,
        'address' => $request->address,
        'province' => $request->province,
        'city' => $request->city,
    ]);

    // Prepare all booking details for the email
    $bookingDetails = [
        'id' => $booking->id,
        'name' => $booking->name,
        'contact' => $booking->contact,
        'email' => $booking->email,
        'site_visit_date' => $booking->site_visit_date,
        'address' => $booking->address,
        'province' => $booking->province,
        'city' => $booking->city,
        'user_id' => $booking->user_id,
    ];

    // Get user email from the user_id and send the email
    $userEmail = User::find($booking->user_id)->email;
    Mail::to($userEmail)->send(new BookingSuccessMail($bookingDetails));

    // Add a task log entry for the created booking
    TaskLog::create([
        'type' => 'Booking',
        'type_id' => $booking->id,
        'action' => 'Created a new booking',
        'action_date' => now(), // Current date and time
        'user_id' => $request->user_id, // Optional: track which user created the booking
    ]);

    $user = User::find($request->user_id);
    Notification::create([
        'user_id' => $request->user_id, // User who created the booking
        'sent_to' => 1, // ID of the recipient for this notification
        'title' => 'New Booking Created',
        'message' => $user->name . ' made a new booking with ID: ' . $booking->id,
        'sent_at' => now(),
        'type' => 'Booking', // Set type to Booking
        'type_id' => $booking->id // Set type_id to the ID of the booking
    ]);

    return response()->json(['message' => 'Booking created successfully.']);
}

    
    
public function cancelBooking($id) {
    $booking = Booking::findOrFail($id);
    
    // Check if the booking status is 'pending'
    if ($booking->booking_status === 'pending') {
        $booking->booking_status = 'cancelled'; // Change status to 'cancelled'
        $booking->save();

        // Log the cancellation action
        TaskLog::create([
            'user_id' => auth()->id(), // Get the authenticated user ID
            'type_id' => $booking->id, // Use the booking ID
            'type' => 'Booking', // Set the type for clarity
            'action' => 'Cancel Booking. Booking ID: '. $booking->id, // Specify the action performed
            'action_date' => now(), // Log the date of action
        ]);

        // Retrieve the user who created the booking
        $user = User::find($booking->user_id);
        
        // Create a notification for the booking cancellation
        Notification::create([
            'user_id' => $booking->user_id, // User who created the booking
            'sent_to' => 1, // ID of the recipient for this notification
            'title' => 'Booking Update',
            'message' => $user->name . ' canceled their booking with ID: ' . $booking->id,
            'sent_at' => now(),
            'type' => 'Booking', // Set type to Booking
            'type_id' => $booking->id // Set type_id to the ID of the booking
           
        ]);

        return response()->json(['message' => 'Booking has been Cancelled.']);
    } else {
        return response()->json(['message' => 'Booking cannot be canceled as it is already completed or cancelled.'], 400);
    }
}

    

public function confirmBooking($id)
{
    $booking = Booking::findOrFail($id);

    if ($booking->booking_status === 'pending') {
        $booking->booking_status = 'confirmed'; 
        $booking->save();

        // Send confirmation email to the booking's email
        Mail::to($booking->email)->send(new BookingConfirmed($booking));

        session()->flash('success', 'Booking confirmed successfully!');
        
        // Log the confirmation action
        TaskLog::create([
            'user_id' => auth()->id(), // Get the authenticated user ID
            'type_id' => $booking->id, // Use the relevant task ID
            'type' => 'Booking',        // Set the type for clarity
            'action' => 'Confirm Booking with ID: ' . $booking->id, // Specify the action performed
            'action_date' => now(),     // Log the date of action
        ]);

        // Fetch the user who made the booking
        $user = User::find($booking->user_id); // Assuming booking has a user_id field

        // Check if user exists before creating a notification
        if ($user) {
            Notification::create([
                'user_id' => 1, // User who created the booking
                'sent_to' => $booking->user_id, // Set sent_to to the user who made the booking
                'title' => 'Booking Update',
                'message' =>'Admin confirmed your booking with ID: ' . $booking->id,
                'sent_at' => now(),
                'type' => 'Booking', // Set type to Booking
                'type_id' => $booking->id // Set type_id to the ID of the booking
            ]);
        }

        // Return a response
        return response()->json([
            'message' => 'Booking confirmed successfully.',
            'type' => 'Booking',
            'action' => 'Confirm',
            'action_date' => now(),
        ]);
    } else {
        return response()->json(['message' => 'Booking cannot be confirmed as it is already confirmed or cancelled.'], 400);
    }
}

    
    
    
public function declineBooking($id)
{
    $booking = Booking::findOrFail($id);
    
    // Check if the booking status is 'pending' before declining
    if ($booking->booking_status === 'pending') {
        $booking->booking_status = 'declined'; // Change status to declined
        $booking->save();
        
        // Send decline email to the booking's email
        Mail::to($booking->email)->send(new BookingDeclined($booking));
        
        // Log the decline action
        TaskLog::create([
            'user_id' => auth()->id(), // Get the authenticated user ID
            'type_id' => $booking->id, // Booking ID as task_id
            'type' => 'Booking', // Set type as 'booking'
            'action' => 'Decline Booking with ID: ' . $booking->id, // Action taken
            'action_date' => now(), // Current timestamp
        ]);
        

        // Fetch the user who made the booking
        $user = User::find($booking->user_id); // Assuming booking has a user_id field

        // Check if user exists before creating a notification
        if ($user) {
            Notification::create([
                'user_id' => 1, // User who created the booking
                'sent_to' => $booking->user_id, // Set sent_to to the user who made the booking
                'title' => 'Booking Update',
                'message' => 'Admin declined your booking with ID: ' . $booking->id,
                'sent_at' => now(),
                'type' => 'Booking', // Set type to Booking
                'type_id' => $booking->id // Set type_id to the ID of the booking
            ]);
        }

        session()->flash('success', 'Booking has been declined!'); // Set flash message
        return response()->json(['message' => 'Booking has been declined.']);
    } else {
        // Handle the case where the booking cannot be declined
        return response()->json(['message' => 'Booking cannot be declined as it is already confirmed, visited, or cancelled.'], 400);
    }
}

    
    

public function view($id)
{
    // Fetch the booking using the provided ID
    $booking = Booking::findOrFail($id);

    // Return a view with the booking data
    return view('booking.view', compact('booking'));
}

public function edit($id)
{
    $booking = Booking::findOrFail($id); // Fetch the booking by ID
    $cities = [
        'NCR' => [ // NCR
            ['id' => 'Manila', 'name' => 'Manila'],
            ['id' => 'Quezon City', 'name' => 'Quezon City'],
            ['id' => 'Caloocan', 'name' => 'Caloocan'],
            ['id' => 'Makati', 'name' => 'Makati'],
            ['id' => 'Taguig', 'name' => 'Taguig'],
            ['id' => 'Pasig', 'name' => 'Pasig'],
            ['id' => 'Malabon', 'name' => 'Malabon'],
            ['id' => 'Marikina', 'name' => 'Marikina'],
            ['id' => 'Navotas', 'name' => 'Navotas'],
            ['id' => 'Valenzuela', 'name' => 'Valenzuela'],
            ['id' => 'San Juan', 'name' => 'San Juan'],
            ['id' => 'Pateros', 'name' => 'Pateros'],
        ],
        'CAR' => [ // CAR
            ['id' => 'Baguio City', 'name' => 'Baguio City'],
            ['id' => 'Abra', 'name' => 'Abra'],
            ['id' => 'Apayao', 'name' => 'Apayao'],
            ['id' => 'Benguet', 'name' => 'Benguet'],
            ['id' => 'Ifugao', 'name' => 'Ifugao'],
            ['id' => 'Kalinga', 'name' => 'Kalinga'],
            ['id' => 'Mountain Province', 'name' => 'Mountain Province'],
        ],
        'Ilocos Region' => [ // Ilocos Region
            ['id' => 'Vigan', 'name' => 'Vigan'],
            ['id' => 'Laoag', 'name' => 'Laoag'],
            ['id' => 'Dagupan', 'name' => 'Dagupan'],
            ['id' => 'San Fernando', 'name' => 'San Fernando'],
            ['id' => 'Alaminos', 'name' => 'Alaminos'],
            ['id' => 'Urdaneta', 'name' => 'Urdaneta'],
        ],
        'Cagayan Valley' => [ // Cagayan Valley
            ['id' => 'Tuguegarao', 'name' => 'Tuguegarao'],
            ['id' => 'Ilagan', 'name' => 'Ilagan'],
            ['id' => 'Cauayan', 'name' => 'Cauayan'],
            ['id' => 'Santiago', 'name' => 'Santiago'],
            ['id' => 'Aparri', 'name' => 'Aparri'],
        ],
        'Central Luzon' => [ // Central Luzon
            ['id' => 'San Fernando', 'name' => 'San Fernando'],
            ['id' => 'Angeles', 'name' => 'Angeles'],
            ['id' => 'Olongapo', 'name' => 'Olongapo'],
            ['id' => 'Tarlac', 'name' => 'Tarlac'],
            ['id' => 'Pampanga', 'name' => 'Pampanga'],
            ['id' => 'Bulacan', 'name' => 'Bulacan'],
            ['id' => 'Zambales', 'name' => 'Zambales'],
        ],
        'CALABARZON' => [ // CALABARZON
            ['id' => 'Cavite', 'name' => 'Cavite'],
            ['id' => 'Batangas', 'name' => 'Batangas'],
            ['id' => 'Laguna', 'name' => 'Laguna'],
            ['id' => 'Rizal', 'name' => 'Rizal'],
            ['id' => 'Quezon', 'name' => 'Quezon'],
        ],
        'MIMAROPA' => [ // MIMAROPA
            ['id' => 'Calapan', 'name' => 'Calapan'],
            ['id' => 'Romblon', 'name' => 'Romblon'],
            ['id' => 'Puerto Princesa', 'name' => 'Puerto Princesa'],
            ['id' => 'Marinduque', 'name' => 'Marinduque'],
            ['id' => 'Occidental Mindoro', 'name' => 'Occidental Mindoro'],
            ['id' => 'Oriental Mindoro', 'name' => 'Oriental Mindoro'],
        ],
        'Bicol Region' => [ // Bicol Region
            ['id' => 'Legazpi', 'name' => 'Legazpi'],
            ['id' => 'Naga', 'name' => 'Naga'],
            ['id' => 'Iriga', 'name' => 'Iriga'],
            ['id' => 'Sorsogon', 'name' => 'Sorsogon'],
            ['id' => 'Catanduanes', 'name' => 'Catanduanes'],
        ],
        'Western Visayas' => [ // Western Visayas
            ['id' => 'Iloilo', 'name' => 'Iloilo'],
            ['id' => 'Bacolod', 'name' => 'Bacolod'],
            ['id' => 'Roxas', 'name' => 'Roxas'],
            ['id' => 'Kabankalan', 'name' => 'Kabankalan'],
            ['id' => 'San Carlos', 'name' => 'San Carlos'],
        ],
        'Central Visayas' => [ // Central Visayas
            ['id' => 'Cebu City', 'name' => 'Cebu City'],
            ['id' => 'Tagbilaran', 'name' => 'Tagbilaran'],
            ['id' => 'Dumaguete', 'name' => 'Dumaguete'],
            ['id' => 'Mandaue', 'name' => 'Mandaue'],
            ['id' => 'Lapu-Lapu', 'name' => 'Lapu-Lapu'],
        ],
        'Eastern Visayas' => [ // Eastern Visayas
            ['id' => 'Tacloban', 'name' => 'Tacloban'],
            ['id' => 'Ormoc', 'name' => 'Ormoc'],
            ['id' => 'Calbayog', 'name' => 'Calbayog'],
            ['id' => 'Catbalogan', 'name' => 'Catbalogan'],
            ['id' => 'Borongan', 'name' => 'Borongan'],
        ],
        'Zamboanga Peninsula' => [ // Zamboanga Peninsula
            ['id' => 'Zamboanga City', 'name' => 'Zamboanga City'],
            ['id' => 'Dipolog', 'name' => 'Dipolog'],
            ['id' => 'Dapitan', 'name' => 'Dapitan'],
            ['id' => 'Pagadian', 'name' => 'Pagadian'],
            ['id' => 'Dipolog', 'name' => 'Dipolog'],
        ],
        'Northern Mindanao' => [ // Northern Mindanao
            ['id' => 'Cagayan de Oro', 'name' => 'Cagayan de Oro'],
            ['id' => 'Bukidnon', 'name' => 'Bukidnon'],
            ['id' => 'Misamis Oriental', 'name' => 'Misamis Oriental'],
            ['id' => 'Misamis Occidental', 'name' => 'Misamis Occidental'],
        ],
        'Davao Region' => [ // Davao Region
            ['id' => 'Davao City', 'name' => 'Davao City'],
            ['id' => 'Tagum', 'name' => 'Tagum'],
            ['id' => 'Digos', 'name' => 'Digos'],
            ['id' => 'Panabo', 'name' => 'Panabo'],
            ['id' => 'Samal', 'name' => 'Samal'],
        ],
        'SOCCSKSARGEN' => [ // SOCCSKSARGEN
            ['id' => 'General Santos', 'name' => 'General Santos'],
            ['id' => 'Koronadal', 'name' => 'Koronadal'],
            ['id' => 'Tacurong', 'name' => 'Tacurong'],
            ['id' => 'Kidapawan', 'name' => 'Kidapawan'],
            ['id' => 'Cotabato', 'name' => 'Cotabato'],
        ],
        'Caraga' => [ // Caraga
            ['id' => 'Butuan', 'name' => 'Butuan'],
            ['id' => 'Surigao City', 'name' => 'Surigao City'],
            ['id' => 'Bislig', 'name' => 'Bislig'],
            ['id' => 'Tandag', 'name' => 'Tandag'],
            ['id' => 'Cabadbaran', 'name' => 'Cabadbaran'],
        ],
        'BARMM' => [ // BARMM
            ['id' => 'Cotabato City', 'name' => 'Cotabato City'],
            ['id' => 'Marawi City', 'name' => 'Marawi City'],
            ['id' => 'Lamitan', 'name' => 'Lamitan'],
            ['id' => 'Lanao del Sur', 'name' => 'Lanao del Sur'],
            ['id' => 'Lanao del Norte', 'name' => 'Lanao del Norte'],
            ['id' => 'Basilan', 'name' => 'Basilan'],
            ['id' => 'Sulu', 'name' => 'Sulu'],
            ['id' => 'Tawi-Tawi', 'name' => 'Tawi-Tawi'],
        ],
    ];

    return view('booking.edit', compact('booking', 'cities')); // Return the edit view with the booking data and cities
}



public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'contact' => 'required|string|max:15',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'province' => 'required|string|max:100',
        'site_visit_date' => 'required|date',
    ]);

    $booking = Booking::findOrFail($id);
    
    // Update booking details using only the expected fields
    $booking->update($request->only([
        'name', 
        'email', 
        'contact', 
        'address', 
        'city', 
        'province', 
        'site_visit_date'
    ]));

    // Log the update action
    TaskLog::create([
        'user_id' => auth()->id(), // Get the authenticated user ID
        'type_id' => $booking->id, // Booking ID as task_id
        'type' => 'Booking', // Set type as 'booking'
        'action' => 'Update Booking for Booking ID: '. $booking->id, // Specify the action performed
        'action_date' => now(), // Current timestamp
    ]);

    // Fetch the user who created the booking
    $user = User::find($booking->user_id); // Assuming the booking has a user_id field
    
    // Check if user exists before creating a notification
    if ($user) {
        Notification::create([
            'user_id' => $booking->user_id, // User who created the booking
            'sent_to' => 1, // ID of the recipient for this notification
            'title' => 'Booking Update',
            'message' => $user->name . ' updated their booking with ID: ' . $booking->id,
            'sent_at' => now(),
            'type' => 'Booking', // Set type to Booking
            'type_id' => $booking->id // Set type_id to the ID of the booking
        ]);
    } else {
        // Handle the case where the user does not exist
        // Optionally, you could log this or throw an error
    }

    return redirect()->route('booking.index')->with('success', 'Booking updated successfully.');
}



    
}
