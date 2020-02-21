<?php

namespace App\Policies;

use App\Seat;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class SeatPolicy
 *
 * @package App\Policies
 */
class SeatPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Seat::class;
}
