<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message'  => "Your order #{$this->order->id} has been placed successfully!",
            'order_id' => $this->order->id,
            'total'    => $this->order->total,
            'status'   => $this->order->status,
        ];
    }
}
