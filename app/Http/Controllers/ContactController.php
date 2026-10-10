<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $to = env('CONTACT_TO') ?: config('mail.from.address');

        if (!$to) {
            return redirect()
                ->route('contact')
                ->withInput()
                ->withErrors(['message' => 'Mail is not configured.']);
        }

        Mail::to($to)->send(new ContactFormSubmitted($validated));

        return redirect()->route('contact')->with('status', 'Message sent.');
    }
}
