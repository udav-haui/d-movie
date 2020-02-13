<?php

namespace App\Repositories\Interfaces;

use App\Cinema;

/**
 * Interface ShowInterface
 *
 * @package App\Repositories\Interfaces
 */
interface ShowInterface
{
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
    public function getStatusLabel();

    /**
     * Get id
     *
     * @return string|int
     */
    public function getId();

    /**
     * Set id
     *
     * @param string|int $id
     */
    public function setId($id);

    /**
     * Get status
     *
     * @return string|int
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string|int $status
     */
    public function setStatus($status);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get cinema of this show
     *
     * @return Cinema
     */
    public function getCinema();

    /**
     * Assign show to a cinema
     *
     * @param Cinema $cinema
     */
    public function setCinema(Cinema $cinema);

    /**
     * A show belong to a cinema
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cinema();
}
