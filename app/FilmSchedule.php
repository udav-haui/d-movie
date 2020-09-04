<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FilmSchedule
 *
 * @package App
 */
class FilmSchedule extends AbstractModel
{
    protected $table = 'film_show';

    protected $guarded = [];

    /** Constant fields */
    const ID = 'id';
    const STATUS = 'status';
    const START_DATE = 'start_date';
    const FILM = 'film_id';
    const SHOW = 'show_id';

    /** Constant status of booking */
    const ENABLE = 1;
    const DISABLE = 0;

    /** Constant permission */
    const VIEW = 'schedule-view';
    const CREATE = 'schedule-create';
    const EDIT = 'schedule-edit';
    const DELETE = 'schedule-delete';

    /**
     * Get id
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
        return $this->setAttribute(self::ID, $id);
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
     * @return void
     */
    public function setStatus(int $status)
    {
        return $this->setAttribute(self::STATUS, $status);
    }

    /**
     * Is schedule is enable
     *
     * @return bool
     */
    public function isVisible()
    {
        return (int)$this->getStatus() === self::ENABLE;
    }

    /**
     * Get start date
     *
     * @return string
     */
    public function getStartDate()
    {
        return $this->getAttribute(self::START_DATE);
    }

    /**
     * Get format date
     *
     * @param string $format
     * @return string
     */
    public function getFormatStartDate(string $format = 'Y-m-d')
    {
        return \Carbon\Carbon::make($this->getStartDate())->format($format);
    }

    /**
     * Set start date
     *
     * @param string $startDate
     * @return void
     */
    public function setStartDate(string $startDate)
    {
        return $this->setAttribute(self::START_DATE, $startDate);
    }

    /**
     * Get associate film
     *
     * @return Film
     */
    public function getFilm()
    {
        return $this->film;
    }

    /**
     * Associate a film to schedule
     *
     * @param Film $film
     * @return Model
     */
    public function setFilm(\App\Film $film)
    {
        return $this->film()->associate($film);
    }

    /**
     * Get associate show
     *
     * @return Show
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Associate schedule to show
     *
     * @param Show $show
     * @return Model
     */
    public function setShow(\App\Show $show)
    {
        return $this->show()->associate($show);
    }

    /**
     * @return mixed
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * A schedule has many times
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function times()
    {
        return $this->hasMany(Time::class, 'film_show_id');
    }

    /**
     * A schedule associate to a film
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function film()
    {
        return $this->belongsTo(\App\Film::class);
    }

    /**
     * A schedule associate to a show
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function show()
    {
        return $this->belongsTo(\App\Show::class);
    }
}
