<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadFollowUpMail extends Mailable
{
    use Queueable, SerializesModels;

    public Lead $lead;
    public string $messageText;

    public function __construct(Lead $lead, string $messageText)
    {
        $this->lead = $lead;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject('Follow-up from ' . setting('company_name', 'LeadFlow CRM'))
            ->view('emails.lead-followup');
    }
}