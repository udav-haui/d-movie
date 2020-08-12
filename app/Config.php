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

    /**
     * @param array $logData
     */
    public static function renderLogHtml($logData)
    {
        $rawHtml = "<ul>";
        foreach ($logData as $key => $item) {
            if (!is_int($item["key_name"])) {
                $rawHtml .= "<li>";
                $rawHtml .= __(mb_strtoupper($item['action']))
                    . self::mappedAttributeLabel()[$item['key_name'] ?? $item["key_name"]];
            }
            $rawHtml .= "<ul>";
            dd($item);
            if (is_array($item["new_value"])) {
                $rawHtml .= self::renderLogHtml($item["new_value"]);
            }
            $_item = $item;
            $rawHtml .= "<li>";
            if ($_item['old_value'] && !is_array($_item["new_value"])) {
                $rawHtml .= __("Save new")
                    . "&nbsp;<code>"
                    . self::mappedAttributeLabel()[$_item['key_name'] ?? $_item["key_name"]]
                    . "</code>" . __("with value") . "<d-mark-create>"
                    . $_item["new_value"]
                    . "</d-mark-create>";
            } elseif ($_item['old_value'] && !$_item["new_value"] && $_item["action"] == "removed") {
                $rawHtml .= __("Removed value of <code>:keyName</code>",
                    ["keyName" => self::mappedAttributeLabel()[$_item['key_name'] ?? $_item["key_name"]]]
                );
            } else {
                $rawHtml .= __(
                    "Modify value of <code>:keyName</code> from <d-mark-delete class='strike'>:oldValue</d-mark-delete> to <d-mark-update>:newValue</d-mark-update>"
                );
            }
            $rawHtml .= "</li>";
        }
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
