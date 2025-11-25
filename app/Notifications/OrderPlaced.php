<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderPlaced extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
{
    $customerName = $this->order->customer_name 
                    ?: ($this->order->user ? $this->order->user->name : 'N/A');

    return [
        'message' => "New order #{$this->order->id} placed by {$customerName}.",
        'url' => route('admin.orders.show', $this->order->id),
    ];
}

}
