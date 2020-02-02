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
    protected $guarded = [];

    /** Constant slider permission */
    const VIEW = 'slider-view';
    const CREATE = 'slider-create';
    const EDIT = 'slider-edit';
    const DELETE = 'slider-delete';

    /** Constant slider status */
    const ACTIVE = 1;
    const DEACTIVATE = 0;

    /**
     * Get slider item image
     *
     * @return string
     */
    public function getImage()
    {
        return !$this->image ?: Data::STORAGE . $this->getAttribute('image');
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
     * Get text of status
     *
     * @return array|string|null
     */
    public function getStatusLabel()
    {
        return (int)$this->getAttribute('status') === self::ACTIVE ?
            __('Enable') :
            __('Disable');
    }
}
