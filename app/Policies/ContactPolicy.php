<?php

namespace App\Policies;

use App\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy extends ModelPolicyAbstract
{
    use HandlesAuthorization;

    protected $model = Contact::class;
}
