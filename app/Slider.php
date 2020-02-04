<?php

namespace App;

use App\Helper\Data;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Slider
 *
 * @package App
 */
class Slider extends Model
{
    /* Constant slider column */
    const ID = 'id';
    const STATUS = 'status';
    const TITLE = 'title';
    const IMAGE = 'image';
    const HREF = 'href';
    const ORDER = 'order';

    /* Constant slider permission */
    const VIEW = 'slider-view';
    const CREATE = 'slider-create';
    const EDIT = 'slider-edit';
    const DELETE = 'slider-delete';

    /* Constant slider status */
    const ENABLE = 1;
    const DISABLE = 0;

    protected $guarded = [];

    /**
     * Get Identifier
     *
     * @return string|int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * Set Identifier
     *
     * @param string|int $id
     * @return mixed
     */
    public function setId($id)
    {
        return $this->setAttribute(self::ID, $id);
    }

    /**
     * Get status of item
     *
     * @return int|string
     */
    public function getStatus()
    {
        return $this->getAttribute(self::STATUS);
    }

    /**
     * Set status of item
     *
     * @param string|int $status
     * @return void
     */
    public function setStatus($status)
    {
        return $this->setAttribute(self::STATUS, $status);
    }

    /**
     * Get title of item
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttribute(self::TITLE);
    }

    /**
     * Set title of item
     *
     * @param string $title
     * @return mixed|void
     */
    public function setTitle($title)
    {
        return $this->setAttribute(self::TITLE, $title);
    }

    /**
     * Get slider item image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getAttribute(self::IMAGE);
    }

    /**
     * Set image path for item
     *
     * @param string $image
     * @return void
     */
    public function setImage($image)
    {
        return $this->setAttribute(self::IMAGE, $image);
    }

    /**
     * Get link of item
     *
     * @return mixed|string
     */
    public function getHref()
    {
        return $this->getAttribute(self::HREF);
    }

    /**
     * Set link for item
     *
     * @param string $href
     * @return void
     */
    public function setHref($href)
    {
        $this->setAttribute(self::HREF, $href);
    }

    /**
     * Get order
     *
     * @return int|string
     */
    public function getOrder()
    {
        return $this->getAttribute(self::ORDER);
    }

    /**
     * Set order for item
     *
     * @param string|int $order
     * @return void
     */
    public function setOrder($order)
    {
        return $this->setAttribute(self::ORDER, $order);
    }

    /**
     * Get link of image item
     *
     * @return string
     */
    public function renderHtmlHref()
    {
        return !$this->getHref() ?:
            '<a href="' . $this->getHref() .
            '" target="_blank">' . $this->getHref() . '</a>';
    }

    /**
     * Get text of status
     *
     * @return array|string|null
     */
    public function getStatusLabel()
    {
        return (int)$this->getAttribute('status') === self::ENABLE ?
            __('Enable') :
            __('Disable');
    }

    /**
     * Get slide image path
     *
     * @return string
     */
    public function getImagePath()
    {
        return !$this->getImage() ?: Data::STORAGE . $this->getAttribute('image');
    }
}
