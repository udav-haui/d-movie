<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Auth\Authenticatable;

interface LogRepositoryInterface
{
    /**
     * Create new Log by auth user
     *
     * @param \App\User|Authenticatable $user
     * @param array $fields
     */
    public function createByUser($user, $fields);

    /**
     * Add field for insert
     *
     * @param string $attribute
     * @param string|int $value
     * @param array $fields
     * @return array
     */
    public function addToInsert($attribute, $value, $fields);

    /**
     * Return a default field to log
     *
     * @param array $fields
     * @param string $shortMessage
     * @param string $message
     * @param string $action
     * @param string $targetModel
     * @param string|int $targetId
     * @return array
     */
    public function defaultFields(
        $fields,
        $shortMessage,
        $message,
        $action,
        $targetModel,
        $targetId
    );
}
