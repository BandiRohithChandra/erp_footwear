<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $email = $this->subject('New Support Ticket Submitted: ' . $this->data['subject'])
                      ->view('emails.support_ticket')
                      ->with('data', $this->data);

        // Attach file if present
        if (!empty($this->data['attachment'])) {
            $email->attach($this->data['attachment']->getRealPath(), [
                'as' => $this->data['attachment']->getClientOriginalName(),
                'mime' => $this->data['attachment']->getMimeType(),
            ]);
        }

        return $email;
    }
}
