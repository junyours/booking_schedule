<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskLog; // Import your model

class TaskLogController extends Controller
{
    public function index(Request $request)
{
    $query = TaskLog::where('user_id', auth()->id());

    // Filter by type (Booking or Quotation)
    if ($request->filled('type')) {
        $validTypes = ['Booking', 'Quotation'];
        if (in_array($request->type, $validTypes)) {
            $query->where('type', $request->type);
        }
    }

    // Filter by date range (start_date and end_date)
    if ($request->filled('start_date') || $request->filled('end_date')) {
        // If both start_date and end_date are the same, filter for that exact date
        if ($request->start_date && $request->end_date && $request->start_date == $request->end_date) {
            $query->whereDate('action_date', $request->start_date);
        } else {
            // Filter by start date
            if ($request->filled('start_date')) {
                $query->where('action_date', '>=', $request->start_date);
            }
            // Filter by end date
            if ($request->filled('end_date')) {
                $query->where('action_date', '<=', $request->end_date);
            }
        }
    }

    // Get the filtered task logs, ordered by action_date (latest first)
    $taskLogs = $query->orderBy('action_date', 'desc')->paginate(10);

    return view('tasklog.index', compact('taskLogs'));
}


    
    
    


public function adminIndex(Request $request)
{
    $query = TaskLog::query();

    // Filter by user_id: only show logs for the logged-in user
    $query->where('user_id', auth()->id());

    // Filter by type
    if ($request->has('type') && $request->type != '') {
        $type = $request->type;
        
        // If the type is 'service', filter for all types that contain 'service'
        if ($type === 'service') {
            $query->where('type', 'like', '%service%');
        } else {
            $query->where('type', $type);
        }
    }

    // Filter for same day if both start_date and end_date are the same
    if ($request->has('start_date') && $request->has('end_date') && $request->start_date == $request->end_date) {
        $query->whereDate('action_date', $request->start_date);
    } else {
        // Filter by start date
        if ($request->has('start_date') && $request->start_date) {
            $query->where('action_date', '>=', $request->start_date);
        }

        // Filter by end date
        if ($request->has('end_date') && $request->end_date) {
            $query->where('action_date', '<=', $request->end_date);
        }
    }

    $taskLogs = $query->orderBy('action_date', 'desc')->paginate(10); // Adjust the pagination limit as needed

    return view('tasklog.adminIndex', compact('taskLogs'));
}


    
    
    

}
