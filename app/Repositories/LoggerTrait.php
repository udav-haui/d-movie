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
     * @param \Illuminate\Database\Eloquent\Model $modelData
     * @param string $modelNamespace
     * @param null $fullOldData
     * @param array $extraFields
     * @return void
     */
    public function updateLog($modelData, $modelNamespace, $fullOldData = null, $extraFields = [])
    {
        /** @var array $fields */
        $fields = $this->logRepository
            ->defaultFields(
                $extraFields,
                Data::UPDATE_MSG,
                $fullOldData ?? $modelData,
                Data::UPDATE,
                $modelNamespace,
                $modelData->id
            );

        $this->logRepository->createByUser(
            auth()->user(),
            $fields
        );
        Log::channel('dmovie-update')
            ->info('User: ID=[' . auth()->user()->getAuthIdentifier() . '] has updated [' . $modelNamespace . ']: ID=['.$modelData->id.']', $modelData->toArray());
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
}
