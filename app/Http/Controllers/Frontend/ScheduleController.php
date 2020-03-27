<?php

namespace App\Http\Controllers\Frontend;

use App\FilmSchedule;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\FilmScheduleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class ScheduleController
 *
 * @package App\Http\Controllers\Frontend
 */
class ScheduleController extends Controller
{
    /**
     * @var FilmScheduleRepositoryInterface
     */
    private $scheduleRepository;

    /**
     * ScheduleController constructor.
     *
     * @param FilmScheduleRepositoryInterface $scheduleRepository
     */
    public function __construct(FilmScheduleRepositoryInterface $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * Get schedule view
     */
    public function index()
    {
        $visibleDates = $this->prepareVisibleDateFilmData();
        return view('frontend.schedule.index', compact('visibleDates'));
    }

    /**
     * Prepare visible date and film data
     *
     * @return array
     * @throws \Exception
     */
    private function prepareVisibleDateFilmData()
    {
        $visibleScheduleByDates = $this->getVisibleDate()
            ->get()
            ->unique(FilmSchedule::START_DATE);

        $visibleData = [];
        /** @var \App\FilmSchedule $schedule */
        foreach ($visibleScheduleByDates as $schedule) {
            $films = $this->getVisibleShowFilmByDate($schedule->getStartDate());
            if (!empty($films)) {
                $visibleData[$schedule->getStartDate()] = [
                    'date' => $schedule->getStartDate(),
                    'films' => $this->getVisibleShowFilmByDate($schedule->getStartDate())
                ];
            }
        }
        return ($visibleData);
    }

    /**
     * Get visible show film by date
     *
     * Will return array include data of film and it's merged show time
     *
     * @param string $date
     * @return array
     * @throws \Exception
     */
    private function getVisibleShowFilmByDate(string $date)
    {
        $listFilm = [];

        $collection = $this->scheduleRepository->getListByDate($date);
        $maxTimeFilm = 0;
        $timeCount = 0;

        /** @var FilmSchedule $schedule */
        foreach ($collection as $schedule) {
            $times = $schedule->times->toArray();
            $film = $schedule->getFilm();
            if ($film->isOpenSaleTicket()) {
                if (array_key_exists($film->getId(), $listFilm)) {
                    $mergeTimes = array_merge($times, $listFilm[$film->getId()]['times']);
                    $times = unique_multidim_array($mergeTimes, 'start_time');
                    $listFilm[$film->getId()]['times'] = $times;
                } else {
                    $listFilm[$film->getId()] = [
                        'film' => $film,
                        'times' => $times
                    ];
                }

                if ($timeCount < count($listFilm[$film->getId()]['times'])) {
                    $timeCount = count($listFilm[$film->getId()]['times']);
                    $maxTimeFilm = $film->getId();
                }
            }
        }
        if ($maxTimeFilm > 0) {
            $listFilm['max_times_film'] = $maxTimeFilm;
        }
        return ($listFilm);
    }

    /**
     * Get visible date
     *
     * @return \Illuminate\Support\Collection
     */
    private function getVisibleDate()
    {
        try {
            return $this->scheduleRepository->getVisibleDates();
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
