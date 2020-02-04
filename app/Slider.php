<?php

namespace App;

use App\Api\Data\SliderInterface;
use App\Helper\Data;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Slider
 *
 * @package App
 */
class Slider extends Model implements SliderInterface
{
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
     * Get slider item image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getAttribute(self::IMAGE);
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

    /**
     * Get link of image item
     *
     * @return string
     */
    public function getHref()
    {
        return !$this->getAttribute('href') ?:
            '<a href="' .
            $this->getAttribute('href') .
            '" target="_blank">' .
            $this->getAttribute('href') .
            '</a>';
    }

    /**
     * Get href raw text of item
     *
     * @return string
     */
    public function getHrefRaw()
    {
        return $this->getAttribute('href');
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
     * Get status code of item
     *
     * @return string|int
     */
    public function getStatusCode()
    {
        return $this->getAttribute('status');
    }

    /**
     * Get title of item
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttribute('title');
    }
}
