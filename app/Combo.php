<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $guarded = [];

    /** Constant field */
    const ID = 'id';
    const STATUS = 'status';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const PRICE = 'price';

    /** Constant permission */
    const VIEW = 'combo-view';
    const CREATE = 'combo-create';
    const EDIT = 'combo-edit';
    const DELETE = 'combo-delete';

    /** Constant status of combo */
    const ENABLE = 1;
    const DISABLE = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }


    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id)
    {
        return $this->setAttribute(self::ID, $id);
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->getAttribute(self::STATUS);
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->setAttribute(self::STATUS, $status);
    }

    /**
     * @return string
     */
    public function getStatusLabel()
    {
        return (int)$this->getStatus() === self::ENABLE ? __('Enable') : __('Disable');
    }

    /**
     * Get page name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(self::NAME);
    }

    /**
     * Set page name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->setAttribute(self::NAME, $name);
    }

    /**
     * Get description
     *
     * @return string
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
    public function setDescription(string $description)
    {
        return $this->setAttribute(self::DESCRIPTION, $description);
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->getAttribute(self::PRICE);
    }

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price)
    {
        return $this->setAttribute(self::PRICE, $price);
    }
}
