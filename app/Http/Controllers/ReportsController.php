<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Payment;

class ReportsController extends Controller
{
    public function projects()
    {
        // Logic to retrieve project reports
        return view('reports.projects'); // Make sure to create this view file
    }

    public function rates(Request $request)
    {
        // Start a query on the Payment model
        $query = Payment::query()->orderBy('created_at', 'desc');
    
        // Initialize a variable for total revenue
        $totalRevenue = 0;
    
        // Default today's date for single-day filtering
        $today = now()->toDateString();
    
        // Apply date filters if provided
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $startDate = $request->input('start_date', $today); // Default to today if not set
            $endDate = $request->input('end_date', $today);     // Default to today if not set
    
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
    
            // Get all payments when filters are applied (no pagination)
            $payments = $query->get();
            // Calculate total revenue for the filtered payments
            $totalRevenue = $query->sum('amount');
        } else {
            // Use pagination when no filters are applied
            $payments = $query->paginate(8); // Adjust the number as needed for pagination
            $totalRevenue = Payment::sum('amount');
        }
    
        return view('reports.rates', compact('payments', 'totalRevenue')); // Return the view with payments data and total revenue
    }
    
    

    
    
    

    
    
}
