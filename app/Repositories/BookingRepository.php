<?php

namespace App\Repositories;

use App\Booking;
use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\BookingRepositoryInterface;

/**
 * Class BookingRepository
 *
 * @package App\Repositories
 */
class BookingRepository extends CRUDModelAbstract implements BookingRepositoryInterface
{
    use LoggerTrait;
    protected $model = Booking::class;
}
