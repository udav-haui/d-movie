<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cinema
 *
 * @package App
 */
class Cinema extends Model
{
    protected $guarded = [];

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


    public function getStatusLabel()
    {
        return (int)$this->getStatus() === self::ENABLE ? __('Enable') : __('Disable');
    }

    /**
     * A cinema can show many films
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function films()
    {
        return $this->belongsToMany(Film::class);
    }

    /**
     * Get id
     *
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * Set id
     *
     * @param int|string $id
     * @return void
     */
    public function setId($id)
    {
        $this->setAttribute(self::ID, $id);
    }

    /**
     * Get status
     *
     * @return int|mixed|string
     */
    public function getStatus()
    {
        return $this->getAttribute(self::STATUS);
    }

    /**
     * Set status
     *
     * @param int|string $status
     * @return void
     */
    public function setStatus($status)
    {
        $this->setAttribute(self::STATUS, $status);
    }

    /**
     * Get name
     *
     * @return mixed|string
     */
    public function getName()
    {
        return $this->getAttribute(self::NAME);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->setAttribute(self::NAME, $name);
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->getAttribute(self::ADDRESS);
    }

    /**
     * Set address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->setAttribute(self::ADDRESS, $address);
    }

    /**
     * Get province
     *
     * @return mixed|string
     */
    public function getProvince()
    {
        return $this->getAttribute(self::PROVINCE);
    }

    /**
     * Set province
     *
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->setAttribute(self::PROVINCE, $province);
    }

    /**
     * Get phone
     *
     * @return mixed|string
     */
    public function getPhone()
    {
        return $this->getAttribute(self::PHONE);
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return void
     */
    public function setPhone($phone)
    {
        $this->setAttribute(self::PHONE, $phone);
    }

    /**
     * Get description
     *
     * @return mixed|string
     */
    public function getDescription()
    {
        return $this->getAttribute(self::DESCRIPTION);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->setAttribute(self::DESCRIPTION, $description);
    }

    /**
     * A cinema has many show
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shows()
    {
        return $this->hasMany(Show::class);
    }
}
