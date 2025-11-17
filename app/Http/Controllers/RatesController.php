<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rate;
use App\Models\TaskLog; 


class RatesController extends Controller
{
    public function index(Request $request)
{
    // Get filters from the request (if provided)
    $serviceTypeFilter = $request->get('service_type');
    $complexityFilter = $request->get('complexity');
    
    // Apply filters to the query
    $rates = Rate::when($serviceTypeFilter, function ($query) use ($serviceTypeFilter) {
        return $query->where('service_type', 'like', '%' . $serviceTypeFilter . '%');
    })
    ->when($complexityFilter, function ($query) use ($complexityFilter) {
        return $query->where('complexity', '=', $complexityFilter); // Use exact match instead of 'like'
    })
    ->paginate(10); // Paginate results (10 per page)
    
    // Pass the rates and the current filter values to the view
    return view('rates.index', compact('rates', 'serviceTypeFilter', 'complexityFilter'));
}


    public function edit($id)
    {
        $rate = Rate::findOrFail($id); // Fetch the rate by its ID
        return response()->json($rate); // Return the rate as JSON to prefill the modal
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'rate' => 'required|numeric|min:0',  // Ensure the rate is a valid number
        ]);
    
        // Find the rate by its ID
        $rate = Rate::findOrFail($id);
    
        // Store the previous rate (before updating) for logging
        $previousRate = $rate->rate;
    
        // Update only the rate field
        $rate->rate = $request->input('rate');
    
        // Save the updated rate
        $rate->save();
    
        // Log the task
        TaskLog::create([
            'user_id' => auth()->check() ? auth()->id() : null, // If authenticated, use user ID
            'type' => 'Rate',
            'type_id' => $rate->id, // Reference to the rate ID
            'action' => 'Rate updated from ₱' . number_format($previousRate, 2) . ' to ₱' . number_format($request->rate, 2),
            'action_date' => now(), // Current date and time
        ]);
    
        // Return a response, redirecting back with a success message
        return response()->json([
            'message' => 'Rate updated successfully',
        ]);
    }
    
    

    
}
