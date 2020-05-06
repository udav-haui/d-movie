<?php

namespace App\Policies;

use App\Booking;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class BookingPolicy
 *
 * @package App\Policies
 */
class BookingPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Booking::class;

    /**
     * Check if user can print ticket
     * @param User $user
     * @return bool
     */
    public function printTicket(User $user)
    {
        return $user->isAdmin() ||
            $user->canAccess(Booking::PRINT_TICKET);
    }
}
