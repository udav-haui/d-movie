<?php

namespace App\Repositories;

use App\Helper\Data;
use App\Repositories\Interfaces\LogRepositoryInterface;

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
     * @param array $extraFields
     * @return void
     */
    public function updateLog($modelData, $modelNamespace, $extraFields = [])
    {
        /** @var array $fields */
        $fields = $this->logRepository
            ->defaultFields(
                $extraFields,
                Data::UPDATE_MSG,
                $modelData,
                Data::UPDATE,
                $modelNamespace,
                $modelData->id
            );

        $this->logRepository->createByUser(
            auth()->user(),
            $fields
        );
    }

    /**
     * Create new log for insert
     *
     * @param \Illuminate\Database\Eloquent\Model $modelData
     * @param string $modelNamespace
     * @param array $extraFields
     * @return void
     */
    public function createLog($modelData, $modelNamespace, $extraFields = [])
    {
        /** @var array $fields */
        $fields = $this->logRepository
            ->defaultFields(
                $extraFields,
                Data::CREATE_MSG,
                $modelData,
                Data::CREATE,
                $modelNamespace,
                $modelData->id
            );

        $this->logRepository->createByUser(
            auth()->user(),
            $fields
        );
    }

    /**
     * Create new log for delete
     *
     * @param \Illuminate\Database\Eloquent\Model $modelData
     * @param string $modelNamespace
     * @param array $extraFields
     * @return void
     */
    public function deleteLog($modelData, $modelNamespace, $extraFields = [])
    {
        /** @var array $fields */
        $fields = $this->logRepository
            ->defaultFields(
                $extraFields,
                Data::DELETE_MSG,
                $modelData,
                Data::DELETE,
                $modelNamespace,
                $modelData->id
            );

        $this->logRepository->createByUser(
            auth()->user(),
            $fields
        );
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
