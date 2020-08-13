<?php

namespace App\Helper;

use App\Log;
use App\Role;

/**
 * Class LogViewHelper
 *
 * @package App\Helper
 */
class LogViewHelper
{
    /**
     * @param Log $log
     */
    public function printLog($log)
    {
        $targetModel = $log->getTargetModel();
        $targetModel = new $targetModel();
        switch ($log->getTargetModel()) {
            case \App\Role::class:
                return $targetModel::renderLogHtml($log->getMessage(), $log);
            case \App\Config::class:
                return $targetModel::renderLogHtml($log->getMessage());
            default;
                return "";
        }
        if ($targetModel instanceof \App\Role) {
            return $targetModel::renderLogHtml($log->getMessage(), $log);
        }
        dd($log->getMessage());
    }
}