<?php

namespace App;

use App\Helper\Data;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Film
 *
 * @package App
 */
class Film extends Model
{
    protected $guarded = [];

    /** Constant field of film */
    const ID = 'id';
    const STATUS = 'status';
    const TITLE = 'title';
    const POSTER = 'poster';
    const DIRECTOR = 'director';
    const CAST = 'cast';
    const GENRE = 'genre';
    const RUNNING_TIME = 'running_time';
    const LANGUAGE = 'language';
    const DESCRIPTION = 'description';
    const RELEASE_DATE = 'release_date';
    const MARK = 'mark';
    const TRAILER = 'trailer';
    const IS_COMING_SOON = 'is_coming_soon';
    const IS_OPEN_SALE_TICKET = 'is_open_sale_ticket';
    const IS_SNEAK_SHOW = 'is_sneak_show';

    /** Constant value */
    /** YES/NO */
    const YES = 1;
    const NO = 0;

    /** Constant permission code for this model */
    const VIEW = 'film-view';
    const CREATE = 'film-create';
    const EDIT = 'film-edit';
    const DELETE = 'film-delete';

    /** Constant status of film */
    const ENABLE = 1;

    /**
     * Render to html
     *
     * @return string
     */
    public function getRenderHtmlPoster()
    {
        return $this->getPoster() !== "" ?
            "<a href=\"{$this->getPosterPath()}\"
            class=\"slide-item\" dm-fancybox
            data-fancybox=\"poster\" data-caption=\"{$this->getTitle()}\">
            <img src=\"{$this->getPosterPath()}\"
                 class=\"slide-item-image\" />
        </a>" : "<p>" . __('No image') . "</p>";
    }

    /**
     * Get formatted date
     *
     * @return string
     */
    public function getFormattedDate()
    {
        return Carbon::make($this->getReleaseDate())->format('d-m-yy');
    }

    /**
     * Get poster path
     *
     * @return string
     */
    public function getPosterPath()
    {
        return !$this->getPoster() ?: Data::STORAGE . $this->getPoster();
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->setAttribute(self::ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->getAttribute(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        $this->setAttribute(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function getPoster()
    {
        return $this->getAttribute(self::POSTER);
    }

    /**
     * @inheritDoc
     */
    public function setPoster($poster)
    {
        $this->setAttribute(self::POSTER, $poster);
    }

    /**
     * @inheritDoc
     */
    public function getDirector()
    {
        return $this->getAttribute(self::DIRECTOR);
    }

    /**
     * @inheritDoc
     */
    public function setDirector($director)
    {
        $this->setAttribute(self::DIRECTOR, $director);
    }

    /**
     * @inheritDoc
     */
    public function getCast()
    {
        return $this->getAttribute(self::CAST);
    }

    /**
     * @inheritDoc
     */
    public function setCast($cast)
    {
        $this->setAttribute(self::CAST, $cast);
    }

    /**
     * @inheritDoc
     */
    public function getGenre()
    {
        return $this->getAttribute(self::GENRE);
    }

    /**
     * @inheritDoc
     */
    public function setGenre($genre)
    {
        $this->setAttribute(self::GENRE, $genre);
    }

    /**
     * @inheritDoc
     */
    public function getRunningTime()
    {
        return $this->getAttribute(self::RUNNING_TIME);
    }

    /**
     * @inheritDoc
     */
    public function setRunningTime($runningTime)
    {
        $this->setAttribute(self::RUNNING_TIME, $runningTime);
    }

    /**
     * @inheritDoc
     */
    public function getLanguage()
    {
        return $this->getAttribute(self::LANGUAGE);
    }

    /**
     * @inheritDoc
     */
    public function setLanguage($language)
    {
        $this->setAttribute(self::LANGUAGE, $language);
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->getAttribute(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription($description)
    {
        return $this->setAttribute(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getReleaseDate()
    {
        return $this->getAttribute(self::RELEASE_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setReleaseDate($releaseDate)
    {
        $this->setAttribute(self::RELEASE_DATE, $releaseDate);
    }

    /**
     * @inheritDoc
     */
    public function getMark()
    {
        return $this->getAttribute(self::MARK);
    }

    public function getAgeMark()
    {
        return $this->getMark() !== 'p' ? (int)str_replace("c", "", $this->getMark()) : "p";
    }

    /**
     * @inheritDoc
     */
    public function setMart($mark)
    {
        $this->setAttribute(self::MARK, $mark);
    }

    /**
     * @inheritDoc
     */
    public function getTrailer()
    {
        return $this->getAttribute(self::TRAILER);
    }

    /**
     * @inheritDoc
     */
    public function setTrailer($trailer)
    {
        $this->setAttribute(self::TRAILER, $trailer);
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
    public function setStatus($status)
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


    /**
     * Get Is comming soon
     *
     * @return string
     */
    public function getIsComingSoon()
    {
        return $this->getAttribute(self::IS_COMING_SOON);
    }

    /**
     * Get coming soon label
     *
     * @return string
     */
    public function getIsComingSoonLabel()
    {
        return (int)$this->getIsComingSoon() === self::YES ? __('Yes') : __('No');
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->getStatus() === self::ENABLE;
    }

    /**
     * Is schedule is coming soon
     * @return bool
     */
    public function isComingSoon()
    {
        return $this->getIsComingSoon() === self::YES;
    }

    /**
     * Set this schedule is coming soon
     * @param int $iCs
     * @return void
     */
    public function setIsComingSoon(int $iCs)
    {
        return $this->setAttribute(self::IS_COMING_SOON, $iCs);
    }

    /**
     * Get is open sale ticket code
     *
     * @return int
     */
    public function getIsOpenSaleTicket()
    {
        return $this->getAttribute(self::IS_OPEN_SALE_TICKET);
    }

    /**
     * Get open sale ticket label
     *
     * @return string
     */
    public function getIsOpenSaleTicketLabel()
    {
        return (int)$this->getIsOpenSaleTicket() === self::YES ? __('Yes') : __('No');
    }

    /**
     * Is schedule opening sale ticket
     *
     * @return bool
     */
    public function isOpenSaleTicket()
    {
        return $this->getIsOpenSaleTicket() === self::YES;
    }

    /**
     * Set schedule is open sale ticket
     *
     * @param int $iOst
     * @return void
     */
    public function setIsOpenSaleTicket(int $iOst)
    {
        return $this->setAttribute(self::IS_OPEN_SALE_TICKET, $iOst);
    }

    /**
     * Get is sneak show code
     *
     * @return int
     */
    public function getIsSneakShow()
    {
        return $this->getAttribute(self::IS_SNEAK_SHOW);
    }

    /**
     * Get sneak show label
     *
     * @return string
     */
    public function getIsSneakShowLabel()
    {
        return (int)$this->getIsSneakShow() === self::YES ? __('Yes') : __('No');
    }

    /**
     * Is schedule sneak show
     *
     * @return bool
     */
    public function isSneakShow()
    {
        return $this->getIsSneakShow() === self::YES;
    }

    /**
     * Set schedule is sneak show
     *
     * @param int $iSs
     * @return void
     */
    public function setIsSneakShow(int $iSs)
    {
        return $this->setAttribute(self::IS_SNEAK_SHOW, $iSs);
    }

    /**
     * A film can be show on many cinema
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function shows()
    {
        return $this->belongsToMany(Show::class)
            ->withPivot('start_date')
            ->withPivot('status')
            ->withPivot('id');
    }

    /**
     * A film has many show times
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function times()
    {
        return $this->hasManyThrough(Time::class, FilmSchedule::class, 'film_id', 'film_show_id', 'id', 'id');
    }

    /**
     * A film has many schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function filmSchedules()
    {
        return $this->hasMany(FilmSchedule::class);
    }

    /**
     * True if this film has any schedule is enable
     *
     * @return bool
     */
    public function isAvailableSchedule()
    {
        return $this->filmSchedules()
                ->where(FilmSchedule::START_DATE, '>=', \Carbon\Carbon::today()->format('Y-m-d'))
                ->where(FilmSchedule::STATUS, FilmSchedule::ENABLE)->count() > 0;
    }

    /**
     * @param string $startDate
     * @param string $startTime
     * @return array
     */
    public function getSelectedSeats(string $startDate, string $startTime)
    {
        $shows = $this->shows()
            ->wherePivot('status', self::ENABLE)
            ->get();

        $times = collect();
        /** @var Show $show */
        foreach ($shows as $show) {
            $times = $times->merge($show->times()
                ->where('times.start_date', $startDate)
                ->whereStartTime($startTime)->get());
        }

        $selectedSeats = [];
        /** @var Time $time */
        foreach ($times as $time) {
            $selectedSeats = array_merge($selectedSeats, $time->seats()
                ->where('tickets.status', '!=', -1)
                ->pluck('seat_id')->toArray());
        }
        return $selectedSeats;
    }

    /**
     * Available for sale ticket
     *
     * @return bool
     */
    public function isAvailableSale()
    {
        $isAvailable = $this->isOpenSaleTicket();

        if ($isAvailable) {
            $shows = $this->shows()
                ->wherePivot(FilmSchedule::STATUS, FilmSchedule::ENABLE)
                ->get();
            $isAvailable = $shows->isNotEmpty();
        }

        return $isAvailable;
    }

    /**
     * @param string $startDate
     * @param string $startTime
     * @return int
     */
    public function getEmptySeats(string $startDate, string $startTime)
    {
        // Get visible schedule
        $filesSchedules = get_visible($this->filmSchedules())->get();

        // Get all visible time
        /** @var Collection $times */
        $times = collect();
        foreach ($filesSchedules as $schedule) {
            $times = $times->merge($schedule->times()->where('times.'.Time::START_DATE, $startDate)
                ->where(Time::START_TIME, $startTime)->get());
        }
        $seatsCount = 0;

        // Get all booking
        /** @var Time $time */
        foreach ($times as $time) {
            $show = $time->show();
            $seatsCount += $show->getVisibleSeats()->count();
            if ($time->seats->count() > 0) {
                $seatsCount -= $time->getVisibleSeats()->count();
            }
        }
        return $seatsCount;
    }

    /**
     * @param int $showId
     * @return Collection|Model|null
     */
    public function getShowById(int $showId)
    {
        return $this->shows()->find($showId);
    }

    /**
     * @param array $showIds
     * @return mixed
     */
    public function getFirstShowsExceptShowId(array $showIds)
    {
        return $this->shows()
            ->wherePivot('status', self::ENABLE)
            ->whereNotIn('shows.id', $showIds)->first();
    }
}
