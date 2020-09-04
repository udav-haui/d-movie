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

    /**
     * @param mixed $option
     * @return string
     */
    public static function getModelName($option = null)
    {
        return 'Unnamed';
    }

    public static function mappedAttributeLabel()
    {
        return [];
    }

    public static function mappedValue($keyForCompare)
    {
        return [];
    }

    public static function renderLogHtml($logData, $customVal = null)
    {
        $rawHtml = '<ul>';
        foreach ($logData as $item) {
//            $rawHtml .= '<li>';
//            $targetModelLogText = __(
//                "<code>:modelName</code>with identifier<code>:id</code>",
//                [
//                    'modelName' => \App\AbstractModel::getModelName(),
//                    'id' => $customVal->getTargetId()
//                ]
//            );
//            $rawHtml .= __(mb_strtoupper($item['action'])) . $targetModelLogText;
//            $rawHtml .= "<ul>";
            if (is_array($item["new_value"])) {
                $rawHtml .= self::renderLogHtml($item["new_value"], $customVal);
            } elseif ($item["action"] == "removed") {
                $rawHtml .= "<li>";
                $rawHtml .= "The model&nbsp;<d-mark-delete class='strikethrough'>" . \App\AbstractModel::getModelName() . "</d-mark-delete> was removed!";
                $rawHtml .= "</li></ul>";
            } else {
                $rawHtml .= "<li>";
                $rawHtml .= __(
                    ":action value of <code>:keyName</code> from <d-mark-delete class='strikethrough'>:oldValue</d-mark-delete> to <d-mark-update>:newValue</d-mark-update>",
                    [
                        "action" => __($item["action"]),
                        "keyName" => isset(self::mappedAttributeLabel()[$item['key_name']]) ?self::mappedAttributeLabel()[$item['key_name']]: $item["key_name"],
                        "oldValue" => self::mappedAttributeLabel()[$item['old_value']] ?? $item['old_value'],
                        "newValue" => self::mappedAttributeLabel()[$item["new_value"]] ?? $item['new_value']
                    ]
                );
                $rawHtml .= "</li></ul>";
            }
        }
        return $rawHtml;
    }
}
