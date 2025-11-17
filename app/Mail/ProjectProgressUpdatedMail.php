<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectProgressUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $phase;
    public $phaseProgress;
    public $remarks;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project, $phase, $phaseProgress, $remarks)
    {
        $this->project = $project;
        $this->phase = $phase;
        $this->phaseProgress = $phaseProgress;
        $this->remarks = $remarks;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@arfil-landscaping.com', 'Arfil\'s Landscaping Services')
                    ->subject('Project Progress Updated')
                    ->view('emails.project_progress_updated');
    }
}
