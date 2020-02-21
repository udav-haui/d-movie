<?php

namespace App\Repositories;

use App\Repositories\Interfaces\LogRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class LogRepository
 *
 * @package App\Repositories
 */
class LogRepository implements LogRepositoryInterface
{

    /**
     * Create new Log by auth user
     *
     * @param \App\User|Authenticatable $user
     * @param array $fields
     */
    public function createByUser($user, $fields)
    {
        $user->logs()->create($fields);
    }

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
    ) {
        $fields += [
            'short_message' => $shortMessage,
            'message' => $message,
            'action' => $action,
            'target_model' => $targetModel,
            'target_id' => $targetId
        ];
        return $fields;
    }

    /**
     * Add field for insert
     *
     * @param string $attribute
     * @param string|int $value
     * @param array $fields
     * @return array
     */
    public function addToInsert($attribute, $value, $fields)
    {
        $fields[$attribute] = $value;
        return $fields;
    }
}
