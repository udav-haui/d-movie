<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Show
 *
 * @package App
 */
class Show extends Model implements \App\Repositories\Interfaces\ShowInterface
{
    protected $guarded = [];

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
     * A show belong to a cinema
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }
}
