<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FirstLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Bienvenido a AAPDPP';
    public $name, $email, $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.FirstLogin');
    }
}
