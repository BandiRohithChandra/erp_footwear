<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TransactionApprovalNotification extends Notification
{
    use Queueable;

    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'transaction_id' => $this->transaction->id,
            'description' => $this->transaction->description,
            'message' => "A new transaction '{$this->transaction->description}' is pending approval.",
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'transaction_id' => $this->transaction->id,
            'description' => $this->transaction->description,
            'message' => "A new transaction '{$this->transaction->description}' is pending approval.",
        ]);
    }
}