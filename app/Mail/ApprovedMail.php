<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $applicant_name;
    public $company_name;
    public $company_email;
    public $invitation_code;
    public $isInvited;

    /**
     * Create a new message instance.
     */
    public function __construct($applicant_name, $company_name, $company_email, $invitation_code, $isInvited)
    {
        $this->applicant_name = $applicant_name;
        $this->company_name = $company_name;
        $this->company_email = $company_email;
        $this->invitation_code = $invitation_code;
        $this->isInvited = $isInvited;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Approved Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.approve',
            with: [
                'applicant_name' => $this->applicant_name,
                'company_name' => $this->company_name,
                'company_email' => $this->company_email,
                'invitation_code' => $this->invitation_code,
                'isInvited' => $this->isInvited,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
