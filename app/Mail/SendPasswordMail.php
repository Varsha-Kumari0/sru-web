<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendPasswordMail extends Mailable
{
    public $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Your Login Password')
                    ->view('emails.password');
    }
}