<?php

// app/Mail/PaymentNotification.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Payment;
use App\Models\Project;

class PaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $project;

    /**
     * Create a new message instance.
     */
    public function __construct(Payment $payment, Project $project)
    {
        $this->payment = $payment;
        $this->project = $project;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from('no-reply@arfil-landscaping.com', 'Arfil\'s Landscaping Services')
                    ->subject('Payment Submitted for Project')
                    ->view('emails.payment_notification');
    }
}
