<?php

namespace App\Http\Controllers\Frontend;

use App\Repositories\Interfaces\FilmRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers\Frontend
 */
class HomeController extends Controller
{
    /**
     * @var FilmRepositoryInterface
     */
    private $filmRepository;

    /**
     * HomeController constructor.
     * @param FilmRepositoryInterface $filmRepository
     */
    public function __construct(FilmRepositoryInterface $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /** @var Collection $visibleFilms */
        $visibleFilms = $this->filmRepository->getVisible(null, ['shows', 'shows.times'])->get();

        $hasScheduleFilms = $visibleFilms->filter(function (\App\Film $film) {
            /** @var Collection $shows */
            return $film->shows->filter(function (\App\Show $show) {
                /** @var Collection $times */
                return $show->times->filter(function (\App\Time $time) {
                    $startDate = \Carbon\Carbon::make($time->getStartDate() . $time->getStartTime());
                    $estimatedTime = \Carbon\Carbon::now();
                    return $startDate->greaterThanOrEqualTo($estimatedTime);
                })->isNotEmpty();
            })->isNotEmpty();
        });

        $films = $hasScheduleFilms;

        return view('frontend.home', compact('films'));
    }
}
