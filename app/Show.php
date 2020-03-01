<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Show
 *
 * @package App
 */
class Show extends Model
{
    protected $guarded = [];

    /** Constant field for show model */
    const ID = 'id';
    const STATUS = 'status';
    const NAME = 'name';
    const CINEMA_ID = 'cinema_id';

    /** Constant status var */
    const ENABLE = 1;
    const DISABLE = 0;

    /** Constant permission */
    const VIEW = 'show-view';
    const CREATE = 'show-create';
    const EDIT = 'show-edit';
    const DELETE = 'show-delete';

    /**
     * @inheritDoc
     */
    public function getStatusLabel()
    {
        return (int)$this->getStatus() === self::ENABLE ? __('Enable') : __('Disable');
    }

    /**
     * Get id
     *
     * @return string|int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * Set id
     *
     * @param string|int $id
     */
    public function setId($id)
    {
        $this->setAttribute(self::ID, $id);
    }

    /**
     * Get status
     *
     * @return string|int
     */
    public function getStatus()
    {
        return $this->getAttribute(self::STATUS);
    }

    /**
     * Set status
     *
     * @param string|int $status
     */
    public function setStatus($status)
    {
        $this->setAttribute(self::STATUS, $status);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(self::NAME);
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->setAttribute(self::NAME, $name);
    }

    /**
     * Get cinema of this show
     *
     * @return Cinema
     */
    public function getCinema()
    {
        return $this->cinema;
    }

    /**
     * Assign show to a cinema
     *
     * @param Cinema $cinema
     */
    public function setCinema(Cinema $cinema)
    {
        $this->cinema()->associate($cinema);
    }

    /**
     * A show has many seats
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getVisibleSeats()
    {
        return $this->seats()->where('seats.'.Seat::STATUS, Seat::ENABLE);
    }

    /**
     * A show belong to a cinema
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function films()
    {
        return $this->belongsToMany(Film::class)
            ->withPivot('start_date')
            ->withPivot('status')
            ->withPivot('id');
    }

    public function times()
    {
        return $this->hasManyThrough(Time::class, FilmSchedule::class, 'show_id', 'film_show_id', 'id', 'id');
    }
}
