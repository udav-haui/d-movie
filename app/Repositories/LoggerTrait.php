<?php

namespace App\Repositories;

use App\Helper\Data;
use App\Repositories\Interfaces\LogRepositoryInterface;
use Log;

/**
 * Trait LoggerTrait
 *
 * @package App\Repositories
 */
trait LoggerTrait
{
    /**
     * @var LogRepositoryInterface
     */
    private $logRepository;

    /**
     * LoggerTrait constructor.
     *
     * @param LogRepositoryInterface $logRepository
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(LogRepositoryInterface $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * Create new log for update
     *
     * @param \Illuminate\Database\Eloquent\Model|array $modelData
     * @param string $modelNamespace
     * @param null $fullOldData
     * @param array $extraFields
     * @return void
     */
    public function updateLog($modelData, $modelNamespace, $fullOldData = null, $extraFields = [])
    {
        $targetId = !is_array($modelData) ? $modelData->id : array_keys($modelData)[0];
        /** @var array $fields */
        $fields = $this->logRepository
            ->defaultFields(
                $extraFields,
                Data::UPDATE_MSG,
                $fullOldData ?? $modelData,
                Data::UPDATE,
                $modelNamespace,
                $targetId
            );

        $this->logRepository->createByUser(
            auth()->user(),
            $fields
        );
        Log::channel('dmovie-update')
            ->info('User: ID=[' . auth()->user()->getAuthIdentifier() . '] has updated [' . $modelNamespace . ']: ID=['.$targetId.']', $modelData);
    }

    /**
     * Create new log for insert
     *
     * @param \Illuminate\Database\Eloquent\Model $modelData
     * @param string $modelNamespace
     * @param null $fullOldData
     * @param array $extraFields
     * @return void
     */
    public function createLog($modelData, $modelNamespace, $fullOldData = null, $extraFields = [])
    {
        /** @var array $fields */
        $fields = $this->logRepository
            ->defaultFields(
                $extraFields,
                Data::CREATE_MSG,
                $fullOldData ?? $modelData,
                Data::CREATE,
                $modelNamespace,
                $modelData->id
            );

        $this->logRepository->createByUser(
            auth()->user(),
            $fields
        );
        Log::channel('dmovie-create')
            ->info('User: ID=[' . auth()->user()->getAuthIdentifier() . '] has created [' . $modelNamespace . ']: ID=['.$modelData->id.']', $modelData->toArray());
    }

    /**
     * Create new log for delete
     *
     * @param \Illuminate\Database\Eloquent\Model $modelData
     * @param string $modelNamespace
     * @param null $fullOldData
     * @param array $extraFields
     * @return void
     */
    public function deleteLog($modelData, $modelNamespace, $fullOldData = null, $extraFields = [])
    {
        /** @var array $fields */
        $fields = $this->logRepository
            ->defaultFields(
                $extraFields,
                Data::DELETE_MSG,
                $fullOldData ?? $modelData,
                Data::DELETE,
                $modelNamespace,
                $modelData->id
            );

        $this->logRepository->createByUser(
            auth()->user(),
            $fields
        );
        Log::channel('dmovie-delete')
            ->info('User: ID=[' . auth()->user()->getAuthIdentifier() . '] has deleted [' . $modelNamespace . ']: ID=['.$modelData->id.']', $modelData->toArray());
    }

    /**
     * Create new log for assign role
     *
     * @param \Illuminate\Database\Eloquent\Model $modelData
     * @param string $modelNamespace
     * @param array $extraFields
     * @return void
     */
    public function assignLog($modelData, $modelNamespace, $extraFields = [])
    {
        /** @var array $fields */
        $fields = $this->logRepository
            ->defaultFields(
                $extraFields,
                Data::ASSIGN_MSG,
                $modelData,
                Data::ASSIGN,
                $modelNamespace,
                $modelData->id
            );

        $this->logRepository->createByUser(
            auth()->user(),
            $fields
        );
    }

    /**
     * Make a log
     *
     * @param mixed|\Illuminate\Database\Eloquent\Model $oldData
     * @param mixed $newData
     * @param string $modelNamespace
     * @param null $getChangedData
     * @param string $logType
     * @param array $noLogCompareFields
     * @param null $identifierKey
     * @param null $fullOldData
     * @param array $extraFields
     */
    public function log(
        $oldData,
        $newData,
        $modelNamespace,
        $getChangedData = null,
        $logType = 'create',
        $noLogCompareFields = [],
        $identifierKey = null,
        $fullOldData = null,
        $extraFields = []
    ) {
        if ($logType == 'update') {
            if ($getChangedData) {
                $this->updateLog($newData, $modelNamespace, $getChangedData, $extraFields = []);
            } else {
                if (!is_array($newData)) {
                    $modelData = clone $newData;
                }
                if (!is_array($oldData)) {
                    $oldData = [$oldData->id => $oldData->toArray()];
                }
                if (!is_array($newData)) {
                    $newData = [$newData->id => $newData->toArray()];
                }
                if ($noLogCompareFields) {
                    $oldData = unset_no_compare_field($oldData, $noLogCompareFields);
                    $newData = unset_no_compare_field($newData, $noLogCompareFields);
                }
                dd($oldData, $newData);
                $logData = $this->arrayDiffRecursive($oldData, $newData, $identifierKey);
                $this->updateLog($modelData ?? $newData, $modelNamespace, $logData, $extraFields = []);
            }
        }
    }

    /**
     * Get messages for messages columns
     *
     * @param array $oldArray
     * @param array $newArray
     * @param null $identifierKey
     * @param bool $isLogChildOldData
     * @return array
     */
    protected function arrayDiffRecursive($oldArray, $newArray, $identifierKey = null, $isLogChildOldData = true)
    {
        $messages = [];
        $removedKeyFromNew = array_diff_key($oldArray, $newArray);
        foreach ($removedKeyFromNew as $key => $value) {
            $messages[$key] = [
                'key_name' => $key,
                'action' => 'removed',
                'new_value' => null,
                'old_value' => $value
            ];
        }
        $updatedKeyFromNew = array_udiff_assoc($newArray, $oldArray, [$this, "compareArray"]);
        $addedNewKeyFromNew = array_diff_key($newArray, $oldArray);
        foreach ($addedNewKeyFromNew as $key => $value) {
            if (is_array($value)) {
                $newValue = $this->arrayDiffRecursive([], $value, null, false);
            } else {
                $newValue = null;
            }
            $messages[$key] = [
                'key_name' => $key,
                'action' => 'updated',
                'new_value' => $newValue ?? $value,
                'old_value' => null
            ];
            unset($updatedKeyFromNew[$key]);
        }
        foreach ($updatedKeyFromNew as $key => $value) {
            $action = 'updated';
            if ($value == null || $value == '') {
                $action = 'removed';
            }
            if (is_array($value)) {
                $newValue = $this->arrayDiffRecursive($oldArray[$key], $value, null, false);
            } else {
                $newValue = null;
            }
            if (!is_array($oldArray[$key])) {
                $oldVal = $oldArray[$key];
            } elseif ($isLogChildOldData) {
                $oldVal = $oldArray[$key];
            } else {
                $oldVal = null;
            }
            $messages[$key] = [
                'key_name' => $key,
                'action' => $action,
                'new_value' => $newValue ?? $value,
                'old_value' => $oldVal
            ];
        }
        return $identifierKey ? [$identifierKey => $messages] : $messages;
    }

    /**
     * @param mixed $firstArrVal
     * @param mixed $secondArrVal
     * @return int
     */
    public static function compareArray($firstArrVal, $secondArrVal)
    {
//        if (is_array($firstArrVal) || is_array($secondArrVal)) {
//            dump($firstArrVal, $secondArrVal);
//            dd($firstArrVal == $secondArrVal);
//        }
        return $firstArrVal == $secondArrVal ? 0 : -1;
    }
}
