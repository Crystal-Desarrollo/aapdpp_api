<?php

namespace App\Http\Controllers;

use App\Http\Requests\BroadcastRequest;
use App\Http\Requests\ContactUsRequest;
use App\Mail\ContactFormEmail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function contactUs(ContactUsRequest $request)
    {
        $validated = $request->validated();
        $mail = new ContactFormEmail($validated);
        $adminEmail = env('ADMIN_MAIL_ADDRESS');
        Mail::to($adminEmail)->send($mail);
    }

    public function broadcast(BroadcastRequest $request)
    {
        // $validated = $request->validated();
        // $mail = new ContactFormEmail($validated);
        // $adminEmail = env('ADMIN_MAIL_ADDRESS');
        // Mail::to($adminEmail)->send($mail);
    }
}
