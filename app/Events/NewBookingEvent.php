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
 * Class NewBookingEvent
 *
 * @package App\Events
 */
class NewBookingEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var \App\Time */
    protected $time;
    protected $seats;

    /**
     * Create a new event instance.
     *
     * @param \App\Time $time
     * @param $seats
     *
     * @return void
     */
    public function __construct(\App\Time $time, $seats)
    {
        $this->time = $time;
        $this->seats = $seats;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('time.newbooking.'.$this->time->getId());
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'time_newBooking';
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
