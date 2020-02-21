<?php

namespace App\Repositories;

use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\TimeRepositoryInterface;
use App\Time;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\MessageBag;

/**
 * Class TimeRepository
 *
 * @package App\Repositories
 */
class TimeRepository extends CRUDModelAbstract implements TimeRepositoryInterface
{
    use LoggerTrait;

    protected $model = Time::class;

    /**
     * Create new showtime
     *
     * @param array $fields
     * @param bool $isWriteLog
     * @return MessageBag|mixed
     * @throws \Exception
     */
    public function create($fields = [], bool $isWriteLog = true)
    {
        if (array_key_exists('prepare_time', $fields)) {
            unset($fields['prepare_time']);
        }

        /** @var \App\FilmSchedule $schedule */
        $schedule = \App\FilmSchedule::find($fields['film_show_id']);

        if ($schedule) {
            $validator = $this->validateExistShowtime($schedule, null, $fields);

            if ($validator) {
                return $validator;
            }

            $fields[Time::START_DATE] = $schedule->getStartDate();

            return parent::create($fields);

        } else {
            throw new \Exception(__('We cant find this schedule: :id', ['id' => $fields['film_show_id']]));
        }
    }

    /**
     * Validate Exist showtime
     *
     * @param \App\FilmSchedule $schedule
     * @param int|null $id
     * @param array $fields
     * @return bool|MessageBag
     */
    public function validateExistShowtime(\App\FilmSchedule $schedule, int $id = null, $fields = [])
    {
        $startTime = $fields[Time::START_TIME] ?? '00:00';
        $stopTime = $fields[Time::STOP_TIME] ?? '00:00';
        $totalTime = $fields[Time::TOTAL_TIME] ?? 0;
        /** @var \Illuminate\Database\Eloquent\Collection $times */
        $times = Time::where('film_show_id', function ($query) use ($schedule) {
            /** @var Builder $query */
            $query->select('film_show.id')
                ->from('film_show')
                ->whereColumn('film_show.id', 'id')
                ->where('show_id', $schedule->getShow()->getId())
                ->where('start_date', $schedule->getStartDate());
        })->where('id', '!=', $id)->get();

        /** @var Time $time */
        foreach ($times as $key => $time) {
            $oldStartTime = Carbon::make($time->getStartDate() . $time->getStartTime());
            $oldStopTime = Carbon::make($time->getStartDate() . $time->getStartTime())
                ->addMinutes($time->getTotalTime());
            $newStartTime = Carbon::make($schedule->getStartDate().$startTime);
            $newStopTime = Carbon::make($schedule->getStartDate().$startTime)->addMinutes($totalTime);

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
                        'showName' => $schedule->getShow()->getName()
                    ]
                )
            );
            return $msgBag;
        }

        return false;
    }

    public function update($modelId = null, $model = null, $fields = [], bool $isWriteLog = true)
    {
        try {
            if (array_key_exists('prepare_time', $fields)) {
                unset($fields['prepare_time']);
            }

            $validator = $this->validateExistShowtime($model->getSchedule(), $model->getId(), $fields);

            if ($validator) {
                return $validator;
            }
            return parent::update($modelId, $model, $fields, $isWriteLog); // TODO: Change the autogenerated stub

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }
}
