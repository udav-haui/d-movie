<?php

namespace App\Repositories;

use App\FilmSchedule as Schedule;
use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\FilmScheduleRepositoryInterface;
use App\Time;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\MessageBag;

/**
 * Class FilmScheduleRepository
 *
 * @package App\Repositories
 */
class FilmScheduleRepository extends CRUDModelAbstract implements FilmScheduleRepositoryInterface
{
    protected $model = Schedule::class;

    /**
     * @param array $fields
     * @param bool $isWriteLog
     * @return Schedule|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|MessageBag|mixed|object|null
     * @throws \Exception
     */
    public function create($fields = [], bool $isWriteLog = true)
    {
        try {
            if (array_key_exists('cinema_id', $fields)) {
                unset($fields['cinema_id']);
            }
            if (array_key_exists('prepare_time', $fields)) {
                unset($fields['prepare_time']);
            }

            $startTime = '00:00';
            $stopTime = '00:00';
            $totalTime = 0;

            if (array_key_exists(Time::START_TIME, $fields)) {
                $startTime = $fields[Time::START_TIME];
                unset($fields[Time::START_TIME]);
            }
            if (array_key_exists(Time::STOP_TIME, $fields)) {
                $stopTime = $fields[Time::STOP_TIME];
                unset($fields[Time::STOP_TIME]);
            }
            if (array_key_exists(Time::TOTAL_TIME, $fields)) {
                $totalTime = $fields[Time::TOTAL_TIME];
                unset($fields[Time::TOTAL_TIME]);
            }

            $fields[Schedule::START_DATE] = $this->formatDate($fields[Schedule::START_DATE]);

            /** @var \Illuminate\Database\Eloquent\Collection $times */
            $times = Time::where('film_show_id', function ($query) use ($fields) {
                /** @var Builder $query */
                $query->select('film_show.id')
                    ->from('film_show')
                    ->whereColumn('film_show.id', 'id')
                    ->where('show_id', $fields[Schedule::SHOW])
                    ->where('start_date', $fields[Schedule::START_DATE]);
            })->get();
            /** @var Time $time */
            foreach ($times as $key => $time) {
                $oldStartTime = Carbon::make($time->getStartDate() . $time->getStartTime());
                $oldStopTime = Carbon::make($time->getStartDate() . $time->getStartTime())
                    ->addMinutes($time->getTotalTime());
                $newStartTime = Carbon::make($fields[Schedule::START_DATE].$startTime);
                $newStopTime = Carbon::make($fields[Schedule::START_DATE].$startTime)->addMinutes($totalTime);

                /**
                 *              |===========A==========|
                 *  =====B=====|                         |=======B========
                 */
                if ($newStopTime < $oldStartTime || $newStartTime > $oldStopTime) {
                    $times->forget($key);
                }

                /**
                 *                  |===========A===========
                 *      |=========B==========|
                 */
                /**
                 *                  |===========A===========|
                 *              |==================B===============|
                 */
                /**
                 *              |==================A===============|
                 *                  |===========B===========|
                 */
                /**
                 *              |==================A===============|
                 *                  |===========B============================|
                 */
//                if (($newStartTime <= $oldStartTime && $newStopTime >= $oldStartTime) ||
//                    ($newStartTime <= $oldStartTime && $newStopTime >= $oldStopTime) ||
//                    ($newStartTime >= $oldStartTime && $newStopTime <= $oldStopTime) ||
//                    ($newStartTime <= $oldStopTime && $newStopTime >= $oldStopTime)
//                ) {
//                    continue;
//                }
            }

            if ($times->isNotEmpty()) {
                $msgBag = new MessageBag();
                $msgBag->add(
                    'start_time',
                    __(
                        'Showtime are exist from [:startTime] - [:stopTime] at [:showName] show.',
                        [
                            'startTime' => $startTime,
                            'stopTime' => $stopTime,
                            'showName' => \App\Show::find($fields[Schedule::SHOW])->getName()
                        ]
                    )
                );
                return $msgBag;
            }


            $schedule = $this->getFilter(null, [
                Schedule::START_DATE => $fields[Schedule::START_DATE],
                Schedule::FILM => $fields[Schedule::FILM],
                Schedule::SHOW => $fields[Schedule::SHOW]
            ])->first();

            if (!$schedule) {
                /** @var Schedule $schedule */
                $schedule = parent::create($fields); // TODO: Change the autogenerated stub
            }

            if ($schedule) {
                $time = $schedule->times()->create([
                    Time::START_DATE => $fields[Schedule::START_DATE],
                    Time::START_TIME => $startTime,
                    Time::STOP_TIME => $stopTime,
                    Time::TOTAL_TIME => $totalTime,
                    Time::SCHEDULE => $schedule->getId()
                ]);

                $this->createLog($time, Time::class);
            }

            return $schedule;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function update(
        $modelId = null,
        $schedule = null,
        $fields = [],
        bool $isWriteLog = true,
        bool $encodeSpecChar = true,
        $nonUpdateFields = [],
        $removedToLogFields = [],
        bool $useUpdateInputFieldToLog = false
    ) {
        try {
            if (array_key_exists('cinema_id', $fields)) {
                unset($fields['cinema_id']);
            }
            if (array_key_exists(Schedule::START_DATE, $fields)) {
                /**
                 * If start date is no change
                 */
                if (Carbon::make($schedule->getStartDate())->equalTo(Carbon::make($fields[Schedule::START_DATE]))) {
                    unset($fields[Schedule::START_DATE]);
                } else {
                    $fields[Schedule::START_DATE] = $this->formatDate($fields[Schedule::START_DATE]);
                }
            }
            if (array_key_exists(Schedule::START_DATE, $fields)) {
                $schedule->times()->update([
                    Time::START_DATE => $fields[Schedule::START_DATE]
                ]);
            }

            if ($schedule !== null) {
                $fields = array_diff($fields, $schedule->toArray());
            }

            return parent::update($modelId, $schedule, $fields, $isWriteLog);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Get visible schedule date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function getVisibleDates()
    {
        /** @var \Illuminate\Database\Eloquent\Builder $visibleDates */
        $visibleDates = $this->getVisible()
            ->where(Schedule::START_DATE, '>=', Carbon::today()->format('Y-m-d'))->orderBy(Schedule::START_DATE, 'ASC');

        return ($visibleDates);
    }

    /**
     * Get list schedule by date
     *
     * @param string $date
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|null[]
     * @throws \Exception
     */
    public function getListByDate(string $date)
    {
        $collection = $this->getVisible();
        return $this->getFilter($collection, [\App\FilmSchedule::START_DATE => $date])->get();
    }
}
