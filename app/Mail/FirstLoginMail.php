<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FirstLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Bienvenido a AAPDPP';
    public $name, $email, $password, $profileUrl;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = $password;
        $this->profileUrl = env('APP_FRONTEND_URL') . "/ingresar";
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
