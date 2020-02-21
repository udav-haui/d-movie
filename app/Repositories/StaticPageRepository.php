<?php

namespace App\Repositories;

use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\StaticPageRepositoryInterface;
use App\StaticPage;

/**
 * Class StaticPageRepository
 *
 * @package App\Repositories
 */
class StaticPageRepository extends CRUDModelAbstract implements StaticPageRepositoryInterface
{
    use LoggerTrait;

    protected $model = StaticPage::class;
}
