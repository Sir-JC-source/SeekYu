<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\RegisteredUsers;

class LoginCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;
    public $password;

    /**
     * Create a new message instance.
     * 
     * @param RegisteredUsers $user
     * @param string $verificationUrl
     * @param string|null $password (The plain-text password)
     */
    public function __construct(RegisteredUsers $user, string $verificationUrl, string $password = null)
    {
        $this->user = $user;
        $this->verificationUrl = $verificationUrl;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Login Credentials - SeekYu HRIS',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.login-credentials',
            // Explicitly passing the variables to ensure they are available in Blade
            with: [
                'user' => $this->user,
                'password' => $this->password,
                'verificationUrl' => $this->verificationUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}