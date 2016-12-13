<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadTest implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $data;
    public $test = 'hello';
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data = User::find(21)->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('test-channel.'.$this->data);
    }
}
