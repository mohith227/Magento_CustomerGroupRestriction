<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 3/5/22
 * Time: 4:01 PM
 */

namespace Mohith\CustomerGroupRestriction\Model;

use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\Serialize\Serializer\Json as Serialize;
use Magento\Store\Model\StoreManagerInterface;
use Mohith\CustomerGroupRestriction\Model\Config\CustomerGroupRestrictionConfig;
use Psr\Log\LoggerInterface;

/**
 * Class CmsPage
 * @package Mohith\CustomerGroupRestriction\Model
 */
class CmsPage
{
    /**
     * @var CustomerSessionFactory
     */
    private $customerSessionFactory;
    /**
     * @var CustomerGroupRestrictionConfig
     */
    private $customerGroupRestrictionConfig;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Serialize
     */
    private $serialize;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ActionFlag
     */
    private $actionFlag;

    /**
     * ProductPage constructor.
     *
     * @param ActionFlag $actionFlag
     * @param Serialize $serialize
     * @param StoreManagerInterface $storeManager
     * @param CustomerSessionFactory $customerSessionFactory
     * @param CustomerGroupRestrictionConfig $customerGroupRestrictionConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        ActionFlag $actionFlag,
        Serialize $serialize,
        StoreManagerInterface $storeManager,
        CustomerSessionFactory $customerSessionFactory,
        CustomerGroupRestrictionConfig $customerGroupRestrictionConfig,
        LoggerInterface $logger
    )
    {
        $this->actionFlag = $actionFlag;
        $this->serialize = $serialize;
        $this->storeManager = $storeManager;
        $this->customerSessionFactory = $customerSessionFactory;
        $this->customerGroupRestrictionConfig = $customerGroupRestrictionConfig;
        $this->logger = $logger;
    }

    public function getCustomerGroupId()
    {
        try {
            $customerSession = $this->customerSessionFactory->create();
            $customerGroupId = 0;
            if ($customerSession->isLoggedIn()) {
                $customerGroupId = $customerSession->getCustomerGroupId();
            }
            return $customerGroupId;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @param $unSerializeCustomerGroupRestrictionData
     * @param $customerGroupId
     * @return mixed
     */
    public function getRedirectUrl($unSerializeCustomerGroupRestrictionData, $customerGroupId)
    {
        try {
            if ($this->customerGroupRestrictionConfig->getRedirect()
                && $unSerializeCustomerGroupRestrictionData) {
                foreach ($unSerializeCustomerGroupRestrictionData as $keyData => $rowData) {
                    if ($customerGroupId == $rowData['customer_group']) {
                        return $rowData['redirect_url'];
                    }
                }
            } else {
                return $this->storeManager->getStore()->getBaseUrl();
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getCmsPageUrl()
    {
        try {
            if ($this->customerGroupRestrictionConfig->getIsActive()) {
                $customerGroupId = $this->customerSessionFactory->create()->getCustomerGroupId();
                $customerGroupData = $this->customerGroupRestrictionConfig->getCustomerGroup();
                $unSerializeCustomerGroupData = $this->serialize->unserialize($customerGroupData);
                $customerGroupRestrictionData = $this->customerGroupRestrictionConfig->getCustomerGroupRedirect();
                $unSerializeCustomerGroupRestrictionData = $this->serialize->unserialize($customerGroupRestrictionData);
                if (isset($unSerializeCustomerGroupData)) {
                    foreach ($unSerializeCustomerGroupData as $key => $row) {
                        if ($customerGroupId == $row['customer_group_data']
                            && in_array("CMS Pages", $row['page_option'])
                        ) {
                            return $this->getRedirectUrl(
                                $unSerializeCustomerGroupRestrictionData,
                                $customerGroupId
                            );
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}