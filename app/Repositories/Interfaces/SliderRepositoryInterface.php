<?php

namespace App\Repositories\Interfaces;


use App\Slider;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class SliderRepository
 *
 * @package App\Repositories
 */
interface SliderRepositoryInterface
{
    /**
     * Get slider collection by order
     *
     * @return Collection|Slider[]
     */
    public function allByOrder();

    /**
     * Change slide item status
     *
     * @param string|int $sliderId
     * @param string|int $newStatus
     * @return void
     * @throws Exception
     */
    public function changeStatus($sliderId, $newStatus);
}
