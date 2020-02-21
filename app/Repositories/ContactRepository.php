<?php

namespace App\Repositories;

use App\Contact;
use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\ContactRepositoryInterface;

/**
 * Class ContactRepository
 *
 * @package App\Repositories
 */
class ContactRepository extends CRUDModelAbstract implements ContactRepositoryInterface
{
    use LoggerTrait;

    protected $model = Contact::class;
}
