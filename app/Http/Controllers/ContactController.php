<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        Mail::raw(
            "Name: {$data['name']}\nEmail: {$data['email']}\n\nMessage:\n{$data['message']}",
            function ($mail) use ($data) {
                $mail->to('americanbeautyshop1@gmail.com')
                     ->subject('Contact Form: ' . $data['subject'])
                     ->replyTo($data['email'], $data['name']);
            }
        );

        return back()->with('success', 'Message sent! We\'ll get back to you within 24 hours.');
    }
}
