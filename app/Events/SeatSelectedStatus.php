<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SeatSelectedStatus
 *
 * @package App\Events
 */
class SeatSelectedStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var \App\Time */
    protected $time;
    protected $seat;

    /**
     * Create a new event instance.
     *
     * @param \App\Time $time
     * @param $seat
     */
    public function __construct(\App\Time $time, $seat)
    {
        $this->time = $time;
        $this->seat = $seat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('time.'.$this->time->getId());
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'time';
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'time' => $this->time,
            'seat' => $this->seat
        ];
    }
}
