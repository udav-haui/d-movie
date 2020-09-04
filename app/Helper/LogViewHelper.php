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
     * @return string
     */
    public function printLog($log)
    {
        $targetModel = $log->getTargetModel();
        $targetModel = new $targetModel();
        switch ($log->getTargetModel()) {
            case Role::class:
                return $targetModel::renderLogHtml($log->getMessage(), $log);
            case \App\Config::class:
                $logData = \App\Config::mappingLogData($log->getMessage());
                return $targetModel::renderLogHtml([1=>$logData]);
            default:
                return \App\AbstractModel::renderLogHtml($log->getMessage(), $log);
        }
    }
}
