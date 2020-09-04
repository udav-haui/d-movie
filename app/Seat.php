<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Seat
 *
 * @package App
 */
class Seat extends AbstractModel
{
    protected $guarded = [];

    public $timestamps = false;

    /** Constant fields */
    const ID = 'id';
    const STATUS = 'status';
    const TYPE = 'type';
    const ROW = 'row';
    const NUMBER = 'number';
    const SHOW = 'show_id';

    /** CONSTANT TYPE OF SEAT */
    const NORMAL = 0;
    const VIP = 1;
    const DOUBLE = 2;

    /** Constant status of booking */
    const ENABLE = 1;
    const DISABLE = 0;

    /** Constant permission */
    const VIEW = 'booking-view';
    const CREATE = 'booking-create';
    const EDIT = 'booking-edit';
    const DELETE = 'booking-delete';

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * Set id
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id)
    {
        $this->setAttribute(self::ID, $id);
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->getAttribute(self::STATUS);
    }

    /**
     * Get status label
     *
     * @return string
     */
    public function getStatusLabel()
    {
        return (int)$this->getStatus() === self::ENABLE ? __('Enable') : __('Disable');
    }

    /**
     * Set status
     *
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->setAttribute(self::STATUS, $status);
    }

    /**
     * Get type code
     *
     * @return int
     */
    public function getType()
    {
        return $this->getAttribute(self::TYPE);
    }

    /**
     * Get label type of booking
     *
     * @return string
     */
    public function getTypeLabel()
    {
        return $this->getType() === self::NORMAL ? __('Normal booking') :
            ($this->getType() === self::VIP ? __('VIP booking') : __('Double booking'));
    }

    /**
     * Get label type of booking
     *
     * @return string
     */
    public function getSeatTypeLabel()
    {
        return $this->getType() === self::NORMAL ? __('Normal') :
            ($this->getType() === self::VIP ? __('VIP') : __('Double'));
    }

    /**
     * Set type of booking
     *
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->setAttribute(self::TYPE, $type);
    }

    /**
     * Get row of booking
     *
     * @return string
     */
    public function getRow()
    {
        return $this->getAttribute(self::ROW);
    }

    /**
     * Set row of booking
     *
     * @param string $row
     * @return void
     */
    public function setRow(string $row)
    {
        $this->setAttribute(self::ROW, $row);
    }

    /**
     * Get Number of booking
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->getAttribute(self::NUMBER);
    }

    /**
     * Get show
     *
     * @return \App\Show
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Get cinema
     *
     * @return Cinema
     */
    public function getCinema()
    {
        return $this->getShow()->getCinema();
    }

    /**
     * Set show
     *
     * @param Show $show
     * @return void
     */
    public function setShow(\App\Show $show)
    {
        $this->show()->associate($show);
    }

    /**
     * A booking belong to a show
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function show()
    {
        return $this->belongsTo(\App\Show::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function times()
    {
        return $this->belongsToMany(Time::class, 'tickets')
            ->withPivot('id')
            ->withPivot('booking_id')
            ->withPivot('status')
            ->withPivot('ticket_code')
            ->withPivot('price')
            ->withPivot('created_at');
    }

    /**
     * @return string
     */
    public function getSeatName()
    {
        return $this->getRow() . $this->getNumber();
    }
}
