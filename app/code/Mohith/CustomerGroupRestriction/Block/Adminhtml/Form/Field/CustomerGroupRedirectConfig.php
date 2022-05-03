<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 3/5/22
 * Time: 10:37 AM
 */

namespace Mohith\CustomerGroupRestriction\Block\Adminhtml\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Mohith\CustomerGroupRestriction\Block\Adminhtml\Form\Field\CustomerGroupRedirectField;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerGroupRedirectConfig
 * @package Mohith\CustomerGroupRestriction\Block\Adminhtml\Form\Field
 */
class CustomerGroupRedirectConfig extends AbstractFieldArray
{
    protected $customerGroupField;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * CustomerGroupConfig constructor.
     * @param LoggerInterface $logger
     * @param Context $context
     * @param array $data
     * @param SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        LoggerInterface $logger,
        Context $context,
        array $data = [],
        SecureHtmlRenderer $secureRenderer = null
    ) {
        $this->logger = $logger;
        parent::__construct($context, $data, $secureRenderer);
    }

    protected function _prepareToRender()
    {
        try {
            $this->addColumn('customer_group', [
                'label' => __('Customer Group'),
                'renderer' => $this->getCustomerGroupField(),
            ]);
            $this->addColumn('redirect_url', ['label' => __('Redirect URL')]);
            $this->_addAfter = false;
            $this->_addButtonLabel = __('Add Redirect URL ');
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @return CustomerGroupRedirectField
     */
    protected function getCustomerGroupField()
    {
        try {
            if (!$this->customerGroupField) {
                $this->customerGroupField = $this->getLayout()->createBlock(
                    CustomerGroupRedirectField::class,
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                );
            }

            return $this->customerGroupField;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        try {
            $customerGroup = $row->getCustomerGroup();
            $options = [];
            if (isset($customerGroup)) {
                $options['option_' . $this->getCustomerGroupField()->calcOptionHash($customerGroup)]
                    = 'selected="selected"';
            }
            $row->setData('option_extra_attrs', $options);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
