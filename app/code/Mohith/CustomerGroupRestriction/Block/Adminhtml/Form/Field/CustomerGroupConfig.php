<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 29/4/22
 * Time: 3:43 PM
 */

namespace Mohith\CustomerGroupRestriction\Block\Adminhtml\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerGroupConfig
 * @package Mohith\CustomerGroupRestriction\Block\Adminhtml\From\Field
 */
class CustomerGroupConfig extends AbstractFieldArray
{
    protected $customerOptionField;
    protected $pageOptionField;
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
            $this->addColumn('customer_group_data', [
                'label' => __('Customer Group'),
                'renderer' => $this->getCustomerGroupField(),
            ]);
            $this->addColumn('page_option', [
                'label' => __('Page Option'),
                'renderer' => $this->getPageFieldRenderer(),
                'extra_params' => 'multiple="multiple"'
            ]);

            $this->_addAfter = false;
            $this->_addButtonLabel = __('Add Customer Group');
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @return CustomerGroupField
     */
    protected function getCustomerGroupField()
    {
        try {
            if (!$this->customerOptionField) {
                $this->customerOptionField = $this->getLayout()->createBlock(
                    CustomerGroupField::class,
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                );
            }

            return $this->customerOptionField;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @return PageField
     */
    protected function getPageFieldRenderer()
    {
        try {
            if (!$this->pageOptionField) {
                $this->pageOptionField = $this->getLayout()->createBlock(
                    PageField::class,
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                );
            }

            return $this->pageOptionField;
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
            $customerGroup = $row->getCustomerGroupData();
            $pages = $row->getPageOption();
            $options = [];
            if (isset($customerGroup)) {
                $options['option_' . $this->getCustomerGroupField()->calcOptionHash($customerGroup)]
                    = 'selected="selected"';
                if (isset($pages)) {
                    foreach ($pages as $page) {
                        $options['option_' . $this->getPageFieldRenderer()->calcOptionHash($page)]
                            = 'selected="selected"';
                    }
                }
            }
            $row->setData('option_extra_attrs', $options);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
