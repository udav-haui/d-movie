<?php

namespace App;

use Carbon\Carbon;

/**
 * Class AbstractModel
 *
 * @package App
 */
abstract class AbstractModel extends \Illuminate\Database\Eloquent\Model
{
    /** Constant permission of model */
    const VIEW = 'view';
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * Get formatted input date
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    public function getFormattedDate($date = '', $format = 'd-m-yy')
    {
        if (empty($date)) {
            return Carbon::now()->format($format);
        }
        return Carbon::make($date)->format($format);
    }
}
