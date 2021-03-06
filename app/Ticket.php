<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ticket
 *
 * @package App
 */
class Ticket extends AbstractModel
{
    protected $guarded = [];

    /** Constant field */
    const ID = 'id';
    const BOOKING = 'booking_id';
    const STATUS = 'status';
    const TICKET_CODE = 'ticket_code';
    const PRICE = 'price';
    const SEAT = 'seat_id';
    const TIME = 'time_id';

    /** Constant status of ticket */
    const ENABLE = 1;
    const NOT_AVAILABLE = -1;

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function time()
    {
        return $this->belongsTo(Time::class);
    }

    /**
     * Get film
     *
     * @return Film
     */
    public function getFilm()
    {
        return $this->getTime()->getFilm();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    /**
     * Get Seat
     *
     * @return Seat
     */
    public function getSeat()
    {
        return $this->seat;
    }

    /**
     * Get related cinema
     *
     * @return Cinema
     */
    public function getCinema()
    {
        return $this->getSeat()->getCinema();
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @return string
     */
    public function getTicketCode()
    {
        return $this->getAttribute(self::TICKET_CODE);
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->getAttribute(self::PRICE);
    }

    /**
     * @return string
     */
    public function getFormatPrice()
    {
        return number_format($this->getPrice(), 0);
    }

    /**
     * @return Time
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->getAttribute(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status)
    {
        $this->setAttribute(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getStatusLabel()
    {
        return (int)$this->getStatus() === self::ENABLE ? __('Enable') : __('Disable');
    }
}
