<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $sender_name;
    public $reciever_name;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$sender_name,$reciever_name)
    {
        \Log::info('MessageSent event triggered: ' . $message);

        $this->message = $message;
        $this->sender_name = $sender_name;
        $this->reciever_name = $reciever_name;


    }
    public function broadcastAs()
    {
        return 'message.sent';
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new Channel('chat');
        return ['chat'];
    }
    // public function broadcastWith()
    // {
    //     \Log::info('BroadcastWith method called with message: ' . $this->message);

    //     return ['message' => $this->message];
    // }
}
