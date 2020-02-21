<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
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

    /** Constant status of seat */
    const ENABLE = 1;
    const DISABLE = 0;

    /** Constant permission */
    const VIEW = 'seat-view';
    const CREATE = 'seat-create';
    const EDIT = 'seat-edit';
    const DELETE = 'seat-delete';

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
     * Get label type of seat
     *
     * @return string
     */
    public function getTypeLabel()
    {
        return $this->getType() === self::NORMAL ? __('Normal seat') :
            ($this->getType() === self::VIP ? __('VIP seat') : __('Double seat'));
    }

    /**
     * Set type of seat
     *
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->setAttribute(self::TYPE, $type);
    }

    /**
     * Get row of seat
     *
     * @return string
     */
    public function getRow()
    {
        return $this->getAttribute(self::ROW);
    }

    /**
     * Set row of seat
     *
     * @param string $row
     * @return void
     */
    public function setRow(string $row)
    {
        $this->setAttribute(self::ROW, $row);
    }

    /**
     * Get Number of seat
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
     * A seat belong to a show
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function show()
    {
        return $this->belongsTo(\App\Show::class);
    }
}
