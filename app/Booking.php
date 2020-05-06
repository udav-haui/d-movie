<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Booking
 *
 * @package App
 */
class Booking extends AbstractModel
{
    protected $guarded = [];

    /** Constant field */
    const ID = 'id';
    const STATUS = 'status';
    const BOOKING_CODE = 'booking_code';
    const QTY = 'qty';
    const AMOUNT = 'amount';
    const MESSAGE = 'message';
    const COMBO = 'combo_id';
    const USER = 'user_id';

    /** Constant status of ticket */
    const SUCCESS = 0;
    const WAITING_FOR_PAYMENT = -1;
    const CANCEL_BY_CUSTOMER = 49;

    /** Constant permission of model */
    const VIEW = 'booking-view';
    const CREATE = 'booking-create';
    const EDIT = 'booking-edit';
    const DELETE = 'booking-delete';
    const PRINT_TICKET = 'print-ticket';

    /**
     * Get formatted date
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    public function getFormattedDate($date = '', $format = 'd-m-yy')
    {
        $date = $this->getCreatedTime();
        return parent::getFormattedDate($date, $format);
    }

    /**
     * Get created time
     *
     * @return Carbon
     */
    public function getCreatedTime()
    {
        return $this->created_at;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get Ticket collection
     *
     * @return Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @return string
     */
    public function getFilmName()
    {
        /** @var Ticket $ticket */
        foreach ($this->tickets as $ticket) {
            return $ticket->getTime()->getFilm()->getTitle();
        }
        return 'N/A';
    }

    /**
     * @return string
     */
    public function getCinemaName()
    {
        /** @var Ticket $ticket */
        foreach ($this->tickets as $ticket) {
            return $ticket->getTime()->show()->getCinema()->getName();
        }
        return 'N/A';
    }

    /**
     * @return string
     */
    public function getShowName()
    {
        /** @var Ticket $ticket */
        foreach ($this->tickets as $ticket) {
            return $ticket->getTime()->show()->getName();
        }
        return 'N/A';
    }

    /**
     * @return string
     */
    public function getSeatsName()
    {
        $seats = [];
        /** @var Ticket $ticket */
        foreach ($this->tickets as $ticket) {
            $seatName = $ticket->getSeat()->getRow() . $ticket->getSeat()->getNumber();
            $seats[] = $seatName;
        }
        return implode(',', $seats);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * @return string
     */
    public function getBookingCode()
    {
        return $this->getAttribute(self::BOOKING_CODE);
    }

    /**
     * @return int
     */
    public function getQty()
    {
        return $this->getAttribute(self::QTY);
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->getAttribute(self::AMOUNT);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getAttribute(self::MESSAGE);
    }

    /**
     * @return Combo
     */
    public function getCombo()
    {
        return $this->combo;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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
        return (int)$this->getStatus() === self::SUCCESS ? __('Success') : __('Failed');
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getStatus() === self::SUCCESS;
    }

    /**
     * @return bool
     */
    public function isWaitingForPayment()
    {
        return $this->getStatus() === self::WAITING_FOR_PAYMENT;
    }
}
