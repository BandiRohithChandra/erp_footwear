<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\User;

class NewClientNotification extends Notification
{
    use Queueable;

    protected $client;

    public function __construct(User $client)
    {
        $this->client = $client;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "New client {$this->client->name} has registered and is awaiting approval.",
            'url' => route('admin.clients.show', $this->client->id),
        ];
    }
}
