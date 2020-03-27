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

            $times = $film->times()->get();
            $availableTimes = $times->filter(function (\App\Time $time) {
                $startDate = \Carbon\Carbon::make($time->getStartDate() . $time->getStartTime());
                $estimatedTime = \Carbon\Carbon::now();
                return $startDate->greaterThanOrEqualTo($estimatedTime);
            });

//            /** @var Collection $shows */
//            $shows = $film->shows->filter(function (\App\Show $show) {
//                /** @var Collection $times */
//                $times = $show->times->filter(function (\App\Time $time) {
//                    $startDate = \Carbon\Carbon::make($time->getStartDate() . $time->getStartTime());
//                    $estimatedTime = \Carbon\Carbon::now();
//                    if ($startDate->greaterThanOrEqualTo($estimatedTime)) {
//                        dump('start='. $startDate);
//                        dump('now='.$estimatedTime);
//                        dump($time->getId());
//                    }
//                    return $startDate->greaterThanOrEqualTo($estimatedTime);
//                });
//                return $times->isNotEmpty();
//            });
            return $availableTimes->isNotEmpty();
        });
        $films = $hasScheduleFilms;

        return view('frontend.home', compact('films'));
    }
}
