<?php

namespace App\Policies;

use App\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Customer::class;
}
