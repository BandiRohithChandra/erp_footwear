<?php

namespace App\Notifications;

use App\Models\WarningLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WarningLetterIssued extends Notification
{
    use Queueable;

    protected $warningLetter;

    public function __construct(WarningLetter $warningLetter)
    {
        $this->warningLetter = $warningLetter;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "You have been issued a warning letter: {$this->warningLetter->reason}",
            'url' => route('employee-portal.warning-letter.show', $this->warningLetter),
        ];
    }
}