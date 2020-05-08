<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewJoinerEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var \App\Time */
    protected $time;
    protected $seats;
    protected $joiner;

    /**
     * Create a new event instance.
     *
     * @param \App\Time $time
     * @param $seats
     * @param $joiner
     *
     * @return void
     */
    public function __construct(\App\Time $time, $seats, $joiner)
    {
        $this->time = $time;
        $this->seats = $seats;
        $this->joiner = $joiner;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('customer.join.'.$this->joiner);
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'customer.join';
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'time' => $this->time,
            'seats' => $this->seats
        ];
    }
}
