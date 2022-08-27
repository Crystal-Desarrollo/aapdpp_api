<?php

namespace App\Http\Controllers;

use App\Http\Requests\BroadcastRequest;
use App\Http\Requests\ContactUsRequest;
use App\Mail\BroadcastEmail;
use App\Mail\ContactFormEmail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function contactUs(ContactUsRequest $request)
    {
        $validated = $request->validated();
        $mail = new ContactFormEmail($validated);
        $adminEmail = env('ADMIN_MAIL_ADDRESS');
        Mail::to($adminEmail)->send($mail);

        return response('Email sent', 200);
    }

    public function broadcast(BroadcastRequest $request)
    {
        $validated = $request->validated();
        $mail = new BroadcastEmail($validated['subject'], $validated['body']);

        $mailTo = $validated['to'];
        if (is_null($mailTo)) {
            $users = User::where('role_id', 2)->get();
            $mailTo = $users->map(fn ($user) => $user->email);
        }

        Mail::to($mailTo)->send($mail);
        return response('Email sent', 200);
    }
}
