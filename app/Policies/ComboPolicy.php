<?php

namespace App\Policies;

use App\Combo;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ComboPolicy
 *
 * @package App\Policies
 */
class ComboPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Combo::class;
}
