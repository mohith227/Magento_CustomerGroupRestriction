<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 29/4/22
 * Time: 3:58 PM
 */

namespace Mohith\CustomerGroupRestriction\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

/**
 * Class PageField
 * @package Mohith\CustomerGroupRestriction\Block\Adminhtml\From\Field
 */
class PageField extends Select
{
    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $options = [
                [
                    'value' => 'CMS Pages',
                    'label' => 'CMS Pages'
                ],
                [
                    'value' => 'Category Pages',
                    'label' => 'Category Pages'
                ],
                [
                    'value' => 'Product Pages',
                    'label' => 'Product Pages'
                ]
            ];
            $this->setOptions($options);
        }
        $this->setExtraParams('multiple="multiple"');

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
        return $this->setName($value . '[]');
    }
}