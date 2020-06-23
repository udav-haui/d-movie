<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 *
 * @package App
 */
class Log extends Model
{
    protected $guarded = [];

    /** Constant field */
    const ID = 'id';
    const SHORT_MESSAGE = 'short_message';
    const MESSAGE = 'message';
    const ACTION = 'action';
    const TARGET_MODEL = 'target_model';
    const TARGET_ID = 'target_id';
    const USER = 'user_id';

    /** Constant permission code for this model */
    const VIEW = 'film-view';

    /** Constant action */
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    protected $casts = [
        self::MESSAGE => 'array'
    ];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id)
    {
        return $this->setAttribute(self::ID, $id);
    }

    /**
     * @return string
     */
    public function getShortMessage()
    {
        return $this->getAttribute(self::SHORT_MESSAGE);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getAttribute(self::MESSAGE);
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->getAttribute(self::ACTION);
    }

    /**
     * @return string
     */
    public function getTargetModel()
    {
        return $this->getAttribute(self::TARGET_MODEL);
    }

    /**
     * @return int
     */
    public function getTargetId()
    {
        return $this->getAttribute(self::TARGET_ID);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * A log belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
