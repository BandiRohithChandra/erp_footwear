<?php

namespace App\Notifications;

use App\Models\ExitEntryRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ExitEntryRequestSubmitted extends Notification
{
    use Queueable;

    protected $exitEntryRequest;

    public function __construct(ExitEntryRequest $exitEntryRequest)
    {
        $this->exitEntryRequest = $exitEntryRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "A new exit/entry request from {$this->exitEntryRequest->employee->user->name} needs your approval.",
            'url' => route('manager-portal.index'),
        ];
    }
}