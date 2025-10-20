<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendFacultyMemberCredentialsMail extends Mailable
{
     use Queueable, SerializesModels;

    public $fullname;
    public $faculty_no;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($fullname, $faculty_no, $password)
    {
        $this->fullname = $fullname;
        $this->faculty_no = $faculty_no;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to SBLMS Faculty Portal - Account Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.facultyMember_credentials',
            with: [
                'fullname' => $this->fullname,
                'faculty_no' => $this->faculty_no,
                'password' => $this->password,
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
