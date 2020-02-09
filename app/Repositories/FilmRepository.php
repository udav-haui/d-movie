<?php

namespace App\Repositories;

use App\Film;
use App\Repositories\Abstracts\CRUDModelAbstract;

/**
 * Class FilmRepository
 *
 * @package App\Repositories
 */
class FilmRepository extends CRUDModelAbstract implements Interfaces\FilmRepositoryInterface
{
    use LoggerTrait;

    protected $model = Film::class;

}
