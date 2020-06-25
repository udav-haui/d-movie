<?php

namespace App\Repositories;

use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\User;

/**
 * Class MemberRepository
 *
 * @package App\Repositories
 */
class MemberRepository extends CRUDModelAbstract implements MemberRepositoryInterface
{
    protected $model = User::class;

    /**
     * @inheritDoc
     */
    public function update(
        $modelId = null,
        $model = null,
        $fields = [],
        bool $isWriteLog = true,
        bool $isEncodeSpecChar = true,
        $nonUpdateFields = [],
        $removedToLogFields = [],
        bool $useUpdateInputFieldToLog = false
    ) {
        $fields[User::DOB] = $this->formatDate($fields[User::DOB]);
        return parent::update($modelId, $model, $fields, $isWriteLog);
    }
}
