<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class FilmScheduleRepository
 *
 * @package App\Repositories
 */
interface FilmScheduleRepositoryInterface
{
    /**
     * Get visible schedule date
     *
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function getVisibleDates();

    /**
     * Get list schedule by date
     *
     * @param string $date
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|null[]
     * @throws \Exception
     */
    public function getListByDate(string $date);
}
