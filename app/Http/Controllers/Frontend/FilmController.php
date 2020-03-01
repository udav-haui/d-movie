<?php

namespace App\Http\Controllers\Frontend;

use App\Film;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ReturnTypeTrait;
use App\Repositories\Interfaces\FilmRepositoryInterface;

/**
 * Class FilmController
 *
 * @package App\Http\Controllers\Frontend
 */
class FilmController extends Controller
{
    use ReturnTypeTrait;

    /**
     * @var FilmRepositoryInterface
     */
    private $filmRepository;

    /**
     * FilmController constructor.
     * @param FilmRepositoryInterface $filmRepository
     */
    public function __construct(FilmRepositoryInterface $filmRepository)
    {

        $this->filmRepository = $filmRepository;
    }

    /**
     * Get film view
     *
     * @param Film $film
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show(Film $film, string $slug)
    {
        if ($film->isVisible()) {
            $releaseDateQuery = $film->filmSchedules()->select(\App\FilmSchedule::START_DATE)->distinct();

            $releaseDate = $this->filmRepository->getVisible($releaseDateQuery)->get();

            return view('frontend.film.show', compact('film', 'releaseDate'));
        }
        return redirect(route('frontend.home'));
    }

    /**
     * @param int $filmId
     * @param int $time
     */
    public function getSeatCount(int $filmId, int $time)
    {
        dd($filmId);
    }

    /**
     * @param Film $film
     * @return Film|\Exception
     */
    public function get(Film $film)
    {
        try {
            $filmUrl = route('fe.filmDetail', ['slug' => convert_vi_to_en($film->getTitle()), 'film' => $film]);
            $film->setAttribute('filmUrl', $filmUrl);
            return $film;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
