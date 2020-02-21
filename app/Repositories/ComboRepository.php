<?php

namespace App\Repositories;

use App\Combo;
use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\ComboRepositoryInterface;

/**
 * Class ComboRepository
 *
 * @package App\Repositories
 */
class ComboRepository extends CRUDModelAbstract implements ComboRepositoryInterface
{
    use LoggerTrait;

    protected $model = Combo::class;
}
