<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OrderPlaced extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // we’ll store in DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "New order #{$this->order->id} placed by {$this->order->user->name}",
            'order_id' => $this->order->id,
            'url' => route('admin.orders.show', $this->order->id), // or any view order route
        ];
    }
}
