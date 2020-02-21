<?php

namespace App\Repositories\Interfaces;


use Illuminate\Support\MessageBag;

/**
 * Class TimeRepository
 *
 * @package App\Repositories
 */
interface TimeRepositoryInterface
{
    /**
     * Validate Exist showtime
     *
     * @param \App\FilmSchedule $schedule
     * @param int|null $id
     * @param array $fields
     * @return bool|MessageBag
     */
    public function validateExistShowtime(\App\FilmSchedule $schedule, int $id = null, $fields = []);
}
