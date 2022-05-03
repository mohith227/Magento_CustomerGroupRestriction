<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 29/4/22
 * Time: 3:56 PM
 */

namespace Mohith\CustomerGroupRestriction\Block\Adminhtml\Form\Field;

use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * Class CustomerGroupField
 * @package Mohith\CustomerGroupRestriction\Block\Adminhtml\From\Field
 */
class CustomerGroupField extends Select
{
    /**
     * @var CollectionFactory
     */
    private $groupCollectionFactory;

    /**
     * CustomerGroupField constructor.
     * @param CollectionFactory $groupCollectionFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        CollectionFactory $groupCollectionFactory,
        Context $context,
        array $data = []
    )
    {
        $this->groupCollectionFactory = $groupCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $options = $this->groupCollectionFactory->create()->loadData()->toOptionArray();
            $this->setOptions($options);
        }

        return parent::_toHtml();
    }

    /**
     * Sets name for input element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }
}