<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;

    /**
     * Create a new message instance.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@arfil-landscaping.com', 'Arfil\'s Landscaping Services')
                     ->subject('New Project Created')
                    ->view('emails.project_created')
                    ->with([
                        'project' => $this->project,  // Pass the project details to the email
                    ]);
    }
}
