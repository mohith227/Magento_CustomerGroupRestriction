<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 29/4/22
 * Time: 4:26 PM
 */

namespace Mohith\CustomerGroupRestriction\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class CustomerGroupRestrictionConfig
 * @package Mohith\CustomerGroupRestriction\Model\Config
 */
class CustomerGroupRestrictionConfig
{
    const XML_GROUP = "general";

    const XML_SECTION = "user_group_restriction";

    const XML_FIELD = "enable";

    /**
     * ScopeConfigInterface
     *
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Array
     *
     * @var array
     */
    private $_data;

    /**
     * UserTrackingConfig constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->_data = $data;
    }

    /**
     * Gets CustomerGroup
     *
     * @return String
     */
    public function getCustomerGroup()
    {
        return $this->getValue(
            'customer_group',
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }

    /**
     * Gets Redirect
     *
     * @return String
     */
    public function getRedirect()
    {
        return $this->getValue(
            'redirect',
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }
    /**
     * Gets CustomerGroupRedirect
     *
     * @return String
     */
    public function getCustomerGroupRedirect()
    {
        return $this->getValue(
            'customer_group_redirect',
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }
    /**
     * GetValue
     *
     * @param $field
     * @param string $group
     * @param string $section
     * @param string $scope
     * @param bool $validateIsActive
     * @return false|mixed
     */
    public function getValue(
        $field,
        $group = self::XML_GROUP,
        $section = self::XML_SECTION,
        $scope = ScopeInterface::SCOPE_STORE,
        $validateIsActive = true
    )
    {
        $path = $section . '/' . $group . '/' . $field;
        if (!array_key_exists($path . $scope, $this->_data)) {
            $this->_data[$path . $scope] = $validateIsActive &&
            !$this->getIsActive() ? false : $this->scopeConfig
                ->getValue($path, $scope);
        }

        return $this->_data[$path . $scope];
    }

    /**
     * Is Active
     *
     * @return bool
     */
    public function getIsActive()
    {
        return (bool)$this->getValue(
            self::XML_FIELD,
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }
}