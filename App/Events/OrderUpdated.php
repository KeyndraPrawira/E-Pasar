<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class OrderUpdated implements ShouldBroadcast
{
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    

    public function broadcastOn()
{
    return [
        new Channel('user.' . $this->order->buyer_id), // untuk buyer
        new Channel('orders'),  
            // untuk semua driver
    ];

    
}
}
