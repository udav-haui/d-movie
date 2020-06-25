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
     * @param bool $isWriteLog
     * @return mixed
     * @throws \Exception
     */
    public function create($fields = [], bool $isWriteLog = true)
    {
        $filePath = '';
        try {
            if (array_key_exists(Film::POSTER, $fields)) {
                $filePath = $fields[Film::POSTER];
                $fields[Film::POSTER] = $this->storeImage($filePath);
            }

            if (array_key_exists(Film::RELEASE_DATE, $fields) && $fields[Film::RELEASE_DATE]) {
                $fields[Film::RELEASE_DATE] = $this->formatDate($fields[Film::RELEASE_DATE]);
            }

            $film = parent::create($fields);

            return $film;
        } catch (\Exception $e) {
            if (array_key_exists(Film::POSTER, $fields)) {
                $this->deleteLocalFile($filePath);
            }
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function update(
        $filmId = null,
        $film = null,
        $fields = [],
        bool $isWriteLog = true,
        bool $encodeSpecChar = true,
        $nonUpdateFields = [],
        $removedToLogFields = [],
        bool $useUpdateInputFieldToLog = false
    ) {
        try {
            if ($filmId !== null) {
                /** @var Film $film */
                $film = $this->find($filmId);
            }

            if ($film) {
                if ($film->getPoster() && array_key_exists(Film::POSTER, $fields)) {
                    $this->deleteLocalFile($film->getPoster());
                    $filePath = $fields[Film::POSTER];
                    $fields[Film::POSTER] = $this->storeImage($filePath);
                }

                if (array_key_exists(Film::RELEASE_DATE, $fields)) {
                    $fields[Film::RELEASE_DATE] = $this->formatDate($fields[Film::RELEASE_DATE]);
                }

                $film = parent::update(null, $film, $fields);

                return $film;
            }

        } catch (\Exception $e) {
            if (array_key_exists(Film::POSTER, $fields) && isset($filePath)) {
                $this->deleteLocalFile($filePath);
            }
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param int|string|null $filmId
     * @param null|Film $film
     * @param bool $isWriteLog
     * @return Film|bool|\Illuminate\Database\Eloquent\Model|void
     * @throws \Exception
     */
    public function delete($filmId = null, $film = null, bool $isWriteLog = true)
    {
        try {
            if ($filmId !== null) {
                $film = $this->find($filmId);
            }
            if ($film) {
                if ($film->getPoster()) {
                    $this->deleteLocalFile($film->getPoster());
                }

                parent::delete(null, $film);

                return $film;
            }
            throw new \Exception(__('We cant find this film.'));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
