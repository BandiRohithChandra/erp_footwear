<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;

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
    return [
        'message' => "Your order #{$this->order->id} status has been updated to '{$this->order->status}'.",
        'url' => route('client.orders.show', $this->order->id),
        'status' => $this->order->status,  // <-- add this
    ];
}

}
