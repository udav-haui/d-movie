<?php

namespace App\Api\Data;

interface SliderInterface
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

    /**
     * Get identifier
     *
     * @return string|int
     */
    public function getId();
}
