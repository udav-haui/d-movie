<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Time
 *
 * @package App
 */
class Time extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    /** Constant field */
    const ID = 'id';
    const START_TIME = 'start_time';
    const STOP_TIME = 'stop_time';
    const SCHEDULE = 'film_show_id';
    const START_DATE = 'start_date';
    const TOTAL_TIME = 'total_time';

    /**
     * @return int
     */
    public function getTotalTime()
    {
        return $this->getAttribute(self::TOTAL_TIME);
    }

    /**
     * @param int $totalTime
     * @return void
     */
    public function setTotalTime(int $totalTime)
    {
        return $this->setAttribute(self::TOTAL_TIME, $totalTime);
    }

    /**
     * Get formatted start time
     *
     * @param string $format
     * @return string
     */
    public function getFormatStartTime($format = 'H:i')
    {
        return Carbon::parse($this->getStartTime())->format($format);
    }

    /**
     * Get formatted stop time
     *
     * @param string $format
     * @return string
     */
    public function getFormatStopTime($format = 'H:i')
    {
        return Carbon::parse($this->getStopTime())->format($format);
    }

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
     * Get start date
     *
     * @return string
     */
    public function getStartDate()
    {
        return $this->getAttribute(self::START_DATE);
    }

    /**
     * Set start time
     *
     * @param string $startDate
     * @return void
     */
    public function setStartDate(string $startDate)
    {
        return $this->setAttribute(self::START_DATE, $startDate);
    }

    /**
     * Get start time
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->getAttribute(self::START_TIME);
    }

    /**
     * Set start time
     *
     * @param string $startTime
     * @return void
     */
    public function setStartTime(string $startTime)
    {
        return $this->setAttribute(self::START_TIME, $startTime);
    }

    /**
     * Get stop time
     *
     * @return string
     */
    public function getStopTime()
    {
        return $this->getAttribute(self::STOP_TIME);
    }

    /**
     * Set stop time
     *
     * @param string $stopTime
     * @return void
     */
    public function setStopTime(string $stopTime)
    {
        return $this->setAttribute(self::STOP_TIME, $stopTime);
    }

    /**
     * Get schedule
     *
     * @return FilmSchedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Associate time for schedule
     *
     * @param FilmSchedule $schedule
     */
    public function setSchedule(FilmSchedule $schedule)
    {
        $this->schedule()->associate($schedule);
    }

    /**
     * A time belong to a schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedule()
    {
        return $this->belongsTo(FilmSchedule::class, 'film_show_id');
    }

    /**
     * @return Show
     */
    public function show()
    {
        return $this->schedule->show;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'tickets')
            ->withPivot('id')
            ->withPivot('booking_id')
            ->withPivot('status')
            ->withPivot('ticket_code')
            ->withPivot('price')
            ->withPivot('created_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getVisibleSeats()
    {
        return $this->seats()->where('seats.'.Seat::STATUS, Seat::ENABLE)
            ->wherePivot(Ticket::STATUS, '!=', Ticket::NOT_AVAILABLE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function film()
    {
        return $this->hasOneThrough(
            Film::class,
            FilmSchedule::class,
            Time::ID,
            Film::ID,
            Time::SCHEDULE,
            FilmSchedule::FILM
        );
    }

    /**
     * Get related film
     *
     * @return Film
     */
    public function getFilm()
    {
        return $this->film;
    }
}
