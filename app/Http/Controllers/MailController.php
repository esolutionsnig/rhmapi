<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Mail;
use App\Mail\NewUserWelcome;
use Auth;

class MailController extends Controller
{
    /**
     * Send welcome email to new added users
     */
    public function welcomeMail()
    {
        Mail::to(Auth::user()->email)->send(new NewUserWelcome());
        return response([
            'message' => "A welcome message has been sent to the newly created user!"
        ], Response::HTTP_OK);
    }
}
