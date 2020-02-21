<?php

namespace App\Policies;

use App\Slider;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SliderPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Slider::class;
}
