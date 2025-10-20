<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendStudentCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullname;
    public $student_no;
    public $password;

    public function __construct($fullname, $student_no, $password)
    {
        $this->fullname = $fullname;
        $this->student_no = $student_no;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Your Student Login Credentials')
                    ->view('emails.student_credentials');
    }
}
