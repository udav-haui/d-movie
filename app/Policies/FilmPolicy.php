<?php

namespace App\Policies;

use App\Film;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilmPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Film::class;
}
