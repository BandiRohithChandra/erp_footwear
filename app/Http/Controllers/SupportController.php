<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'required|string|max:20',
            'company'     => 'nullable|string|max:255',
            'category'    => 'required|string|max:100',
            'priority'    => 'required|string|max:20',
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'attachment'  => 'nullable|file|max:10240', // max 10MB
        ]);

        // Send ticket email to company support
        Mail::send('emails.support-ticket', ['data' => $data], function($mail) use ($data) {
            $mail->to('rohithkohli432.com') // your company support email
                 ->subject('New Support Ticket: ' . $data['subject'])
                 ->replyTo($data['email'], $data['name']); // reply goes to user's email

            // Attach file if uploaded
            if (isset($data['attachment'])) {
                $mail->attach($data['attachment']->getRealPath(), [
                    'as' => $data['attachment']->getClientOriginalName(),
                    'mime' => $data['attachment']->getMimeType(),
                ]);
            }
        });

        return back()->with('success', 'Your support ticket has been submitted successfully!');
    }


    public function index()
{
    return view('support.index'); // make sure you create this view
}



}
