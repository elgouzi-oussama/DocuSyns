<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('user.contact.index');
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Example: send mail to admin
        Mail::raw($request->message, function ($mail) use ($request) {
            $mail->to('admin@docusyns.com')
                ->subject('Contact: ' . $request->subject);
        });

        return back()->with('success', 'Votre message a été envoyé avec succès !');
    }
}
