<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class GetUserNotifications extends Controller
{
    public function getUserNotifications()
    {
        $userId = Auth::id();

        // Fetch notifications where sent_to is the logged-in user
        $notifications = Notification::where('sent_to', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('notifications'));
    }

    public function markAsRead($id)
{
    // Find the notification by ID using the Notification model
    $notification = Notification::find($id);

    if ($notification && $notification->sent_to == auth()->id()) {
        // Update the is_read status to true
        $notification->is_read = true;
        $notification->save();
    }

    // Determine the redirect route based on notification type
    $redirectRoute = null;

    // Check if the sent_to field is 1
    if ($notification->sent_to == 1) {
        $redirectRoute = route('booking.adminShow',$notification->type_id);
    } else {
        // Use the switch statement for other notification types
        switch ($notification->type) {
            case 'Booking':
                $redirectRoute = route('booking.view', $notification->type_id);
                break;
            case 'Project':
                $redirectRoute = route('project.view', $notification->type_id);
                break;
            case 'Payment':
                $redirectRoute = route('payments.show', $notification->type_id);
                break;
            case 'Progress':
                $redirectRoute = route('progress.view', ['projectId' => $notification->type_id]);
                break;
            default:
                $redirectRoute = route('home'); // Fallback route
        }
    }

    // Redirect to the appropriate route
    return redirect($redirectRoute);
}


    public function show($id)
    {
        // Fetch the notification using the Notification model
        $notification = Notification::find($id);

        // Dump the notification data
        var_dump($notification);
        
        // You might want to return a view or do something else here
        return back(); // Redirect back for now
    }
}
