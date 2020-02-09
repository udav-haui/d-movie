<?php

namespace App\Repositories\Interfaces;

/**
 * Interface FilmInterface
 *
 * @package App\Repositories\Interfaces
 */
interface FilmInterface
{
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
    public function getRenderHtmlPoster();

    /**
     * Get poster path
     *
     * @return string
     */
    public function getPosterPath();

    /**
     * @return string
     */
    public function getStatusLabel();

    /**
     * Get id
     *
     * @return int|string
     */
    public function getId();

    /**
     * Set Id
     *
     * @param string|int $id
     * @return void
     */
    public function setId($id);

    /**
     * @return string|int
     */
    public function getStatus();

    /**
     * @param int|string $status
     * @return void
     */
    public function setStatus($status);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title);

    /**
     * Get poster path
     *
     * @return string
     */
    public function getPoster();

    /**
     * Set poster image path
     *
     * @param string $poster
     * @return void
     */
    public function setPoster($poster);

    /**
     * Get director of film
     *
     * @return string
     */
    public function getDirector();

    /**
     * Set director
     *
     * @param string $director
     * @return void
     */
    public function setDirector($director);

    /**
     * @return mixed
     */
    public function getCast();

    /**
     * @param string $cast
     * @return void
     */
    public function setCast($cast);

    /**
     * @return string
     */
    public function getGenre();

    /**
     * @param string $genre
     * @return void
     */
    public function setGenre($genre);

    /**
     * @return string|int
     */
    public function getRunningTime();

    /**
     * @param string|int $runningTime
     * @return void
     */
    public function setRunningTime($runningTime);

    /**
     * @return string
     */
    public function getLanguage();

    /**
     * @param string $language
     * @return void
     */
    public function setLanguage($language);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     * @return void
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getReleaseDate();

    /**
     * @param string $releaseDate
     * @return void
     */
    public function setReleaseDate($releaseDate);

    /**
     * @return string|int
     */
    public function getMark();

    /**
     * @param string|int $mark
     * @return void
     */
    public function setMart($mark);

    /**
     * @return string
     */
    public function getTrailer();

    /**
     * @param string $trailer
     * @return void
     */
    public function setTrailer($trailer);
}
