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

    /**
     * Create new record for new Film
     *
     * @param array $fields
     * @return mixed
     * @throws \Exception
     */
    public function create($fields = [])
    {
        try {
            if ($fields[Film::POSTER]) {
                $fields[Film::POSTER] = $this->storeImage($fields[Film::POSTER]);
            }

            if ($fields[Film::RELEASE_DATE]) {
                $fields[Film::RELEASE_DATE] = $this->formatDate($fields[Film::RELEASE_DATE]);
            }

            $film = parent::create($fields);

            $this->createLog($film, Film::class);

            return $film;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
