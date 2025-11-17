<?php

namespace App\Mail;

use App\Models\Booking; // Import the Booking model
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingDeclined extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->from('no-reply@arfil-landscaping.com', 'Arfil\'s Landscaping Services')
                    ->subject('Your Booking Has Been Declined!')
                    ->view('emails.booking_declined') // Specify the view for the email
                    ->with(['booking' => $this->booking]);
    }
}

