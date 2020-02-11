<?php

namespace App;

use App\Helper\Data;
use App\Repositories\Interfaces\FilmInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Film extends Model implements FilmInterface
{
    protected $guarded = [];

    /**
     * Delete poster file in storage
     *
     * @return bool
     */
    public function deleteLocalPoster()
    {
        return Storage::delete('/public/' . $this->getPoster());
    }

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
}
