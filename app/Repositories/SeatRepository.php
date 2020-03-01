<?php

namespace App\Repositories;

use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Seat;
use Illuminate\Validation\Rule;

/**
 * Class SeatRepository
 *
 * @package App\Repositories
 */
class SeatRepository extends CRUDModelAbstract
{
    use LoggerTrait;

    protected $model = Seat::class;

    /**
     * Create new booking
     *
     * @param array $fields
     * @param bool $isWriteLog
     * @return mixed
     * @throws \Exception
     */
    public function create($fields = [], bool $isWriteLog = true)
    {

        if (array_key_exists('quick_make', $fields)) {
            unset($fields['quick_make']);

            $startPoint = (int)$fields['start_at'];
            $endPoint = (int)$fields['start_at'] + (int)$fields['count'];

            try {
                $successSeat = [];
                $errorSeats = [];
                for ($step = $startPoint; $step < $endPoint; $step++) {
                    $data = [
                        Seat::STATUS => $fields[Seat::STATUS],
                        Seat::SHOW => $fields[Seat::SHOW],
                        Seat::TYPE => $fields[Seat::TYPE],
                        Seat::ROW => $fields[Seat::ROW],
                        Seat::NUMBER => $step
                    ];

                    // Validate if exist a row number
                    $validator = \Validator::make(
                        $data,
                        [
                            Seat::NUMBER => [
                                Rule::unique('seats')->where(function ($query) use ($fields) {
                                    $query->where(Seat::SHOW, $fields[Seat::SHOW])
                                        ->where(Seat::ROW, $fields[Seat::ROW]);
                                })
                            ]
                        ]
                    );

                    if ($validator->fails()) {
                        array_push($errorSeats, $data[Seat::ROW].$data[Seat::NUMBER]);
                    } else {
                        /** @var Seat $seat */
                        $seat = parent::create($data);
                        array_push($successSeat, $seat->getRow().$seat->getNumber());
                    }
                }
                return [
                    'success' => $successSeat,
                    'error' => $errorSeats
                ];
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }

        try {
            $seat = parent::create($fields); // TODO: Change the autogenerated stub

            return $seat;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param null $modelId
     * @param null $model
     * @param bool $isWriteLog
     * @return bool|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function delete($modelId = null, $model = null, bool $isWriteLog = true)
    {
        try {
            return parent::delete($modelId, $model);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
