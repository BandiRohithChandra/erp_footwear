<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class IqamaExpiryNotification extends Notification
{
    use Queueable;

    protected $daysUntilExpiry;

    public function __construct($daysUntilExpiry)
    {
        $this->daysUntilExpiry = $daysUntilExpiry;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('Iqama Expiry Notification'))
                    ->line(__('Your Iqama is expiring in :days days on :date.', [
                        'days' => $this->daysUntilExpiry,
                        'date' => $notifiable->iqama_expiry_date->toDateString(),
                    ]))
                    ->action(__('View Profile'), url('/'))
                    ->line(__('Please take necessary actions to renew your Iqama.'));
    }
}