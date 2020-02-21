<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class FilmSchedulePolicy
 *
 * @package App\Policies
 */
class FilmSchedulePolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = \App\FilmSchedule::class;
}
