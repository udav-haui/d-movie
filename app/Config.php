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
            self::CONFIG_VALUES => __('config.' . self::CONFIG_VALUES)
        ];
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
