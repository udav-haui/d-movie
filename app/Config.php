<?php

namespace App;

/**
 * Class Config
 *
 * @package App
 */
class Config extends AbstractModel
{
    const SALE_PAYMENT_METHODS = 'sales-payment_methods';

    const ID = 'id';
    const SECTION_ID = 'section_id';
    const CONFIG_VALUES = 'config_value';

    const SALES_PAYMENT_METHOD_SECTION_ID = "configuration.sales.payment_methods";

    protected $guarded = [];

    /**
     * Auto cast attribute to array
     *
     * @var string[]
     */
    protected $casts = [
        self::CONFIG_VALUES => 'array'
    ];

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * @inheritDoc
     */
    public static function getModelName($option = null)
    {
        return __('Config');
    }

    /**
     * @inheritDoc
     */
    public static function mappedAttributeLabel()
    {
        return [
            self::CONFIG_VALUES => __('config.' . self::CONFIG_VALUES),
            self::SALES_PAYMENT_METHOD_SECTION_ID => __(self::SALES_PAYMENT_METHOD_SECTION_ID),
            "partner_code" => __("configs.partner_code"),
            "access_key" => __("configs.access_key"),
            "secret_key" => __("configs.secret_key"),
            "end_point" => __("configs.end_point")
        ];
    }

    public static function mappingLogData($logData)
    {
        return current($logData)["new_value"]["config_value"];
    }

    /**
     * @param array $logData
     * @param int $loopTime
     */
    public static function renderLogHtml($logData, $loopTime = 0)
    {
        //$logData = Config::mappingLogData($logData);
        $rawHtml = "";
        foreach ($logData as $key => $item) {
            // print Đã cập nhật thông tin ....
            if ($loopTime == 0) {
                $rawHtml .= "<ul>";
                $rawHtml .= "<li>";
                $rawHtml .= __(mb_strtoupper($item['action']))
                    . isset(self::mappedAttributeLabel()[$item['key_name']]) ?self::mappedAttributeLabel()[$item['key_name']]: $item["key_name"];
            }

            if ($loopTime == 1) {
                $rawHtml .= "<li>";
                $keyName =
                    isset(self::mappedAttributeLabel()[$item["key_name"]]) ?
                    self::mappedAttributeLabel()[$item["key_name"]] :
                    $item["key_name"];

                $rawHtml .= "&nbsp;<code>" . $keyName . "</code>";
            }
            if (is_array($item["new_value"])) {
                $rawHtml .= "<ul>";
                $rawHtml .= self::renderLogHtml($item["new_value"], $loopTime + 1);
            } else {
                $rawHtml .= "<li>";
                if ($item['action'] == "created") {
                    $rawHtml .= __("Save new")
                        . "&nbsp;<code>"
                        . isset(self::mappedAttributeLabel()[$item['key_name']]) ?self::mappedAttributeLabel()[$item['key_name']]: $item["key_name"]
                        . "</code>" . __("with value") . "<d-mark-create>"
                        . $item["new_value"]
                        . "</d-mark-create>";
                } elseif ($item['old_value'] && !$item["new_value"] && $item["action"] == "removed") {
                    $rawHtml .= __("Removed value of <code>:keyName</code>",
                        ["keyName" => self::mappedAttributeLabel()[$item['key_name'] ]?? $item["key_name"]]
                    );
                } else {
                    $rawHtml .= __(
                        "Modify value of <code>:keyName</code> from <d-mark-delete class='strike'>:oldValue</d-mark-delete> to <d-mark-update>:newValue</d-mark-update>",
                        [
                            "keyName" => isset(self::mappedAttributeLabel()[$item['key_name']]) ?self::mappedAttributeLabel()[$item['key_name']]: $item["key_name"],
                            "oldValue" => $item['old_value'],
                            "newValue" => $item["new_value"]
                        ]
                    );
                }
                $rawHtml .= "</li>";
            }
        }
        return $rawHtml;
    }

    /**
     * Set id
     *
     * @param int $id
     * @return Config|mixed
     */
    public function setId($id)
    {
        return $this->setAttribute(self::ID, $id);
    }

    /**
     * Get section id code
     *
     * @return string
     */
    public function getSectionId()
    {
        return $this->getAttribute(self::SECTION_ID);
    }

    /**
     * Set section id code
     *
     * @param string $idCode
     * @return Config|mixed
     */
    public function setSectionId($idCode)
    {
        return $this->setAttribute(self::SECTION_ID, $idCode);
    }

    /**
     * Get config values
     *
     * @return array|null
     */
    public function getConfigValues()
    {
        return $this->getAttribute(self::CONFIG_VALUES);
    }

    /**
     * Set config values
     *
     * @param array $configValues
     * @return Config|mixed
     */
    public function setConfigValues($configValues)
    {
        return $this->setAttribute(self::CONFIG_VALUES, $configValues);
    }
}
