<?php

namespace App\Repositories\Interfaces;

/**
 * Interface CinemaInterface
 *
 * @package App\Repositories\Interfaces
 */
interface CinemaInterface
{
    /** Constant fields of table */
    const ID = 'id';
    const STATUS = 'status';
    const NAME = 'name';
    const ADDRESS = 'address';
    const PROVINCE = 'province';
    const PHONE = 'phone';
    const DESCRIPTION = 'description';

    /** Constant permission of model */
    const VIEW = 'cinema-view';
    const CREATE = 'cinema-create';
    const EDIT = 'cinema-edit';
    const DELETE = 'cinema-delete';

    /** Constant status var*/
    const ENABLE = 1;
    const DISABLE = 0;

    /**
     * Get status label
     *
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
     * Set id
     *
     * @param string|int $id
     * @return void
     */
    public function setId($id);

    /**
     * Get status
     *
     * @return int|string
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string|int $status
     * @return void
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
     * @return void
     */
    public function setName($name);

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress();

    /**
     * Set address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address);

    /**
     * Get province
     *
     * @return string
     */
    public function getProvince();

    /**
     * Set province
     *
     * @param string $province
     * @return void
     */
    public function setProvince($province);

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone();

    /**
     * Set phone
     *
     * @param string $phone
     * @return void
     */
    public function setPhone($phone);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description);

    /**
     * A cinema can show many films
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function films();
}
