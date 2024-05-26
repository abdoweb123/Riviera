<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $user;

    public $roomId;

    public $fromId;

    public $status;

    public function __construct($message, $user, $roomId, $fromId, $status)
    {
        $this->message = $message;
        $this->user = $user;
        $this->roomId = $roomId;
        $this->fromId = $fromId;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('message.'.$this->roomId);
    }

    public function broadcastAs()
    {
        return 'chat-message';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->fromId,
            'name' => $this->user,
            'message' => $this->message,
            'status' => $this->status,

        ];
    }
}
