<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Productslider
 * @copyright   Copyright (c) Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Type;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Mageplaza\Productslider\Model\Config\Source\ProductType;

/**
 * Class Type
 * @package Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Type
 */
class Type extends Generic implements TabInterface
{
    /**
     * Path to template file.
     *
     * @var string
     */
    protected $_template = 'slider/tab/type.phtml';

    /**
     * @var \Mageplaza\Productslider\Model\Config\Source\ProductType
     */
    protected $_productType;

    /**
     * Type constructor.
     * @param \Mageplaza\Productslider\Model\Config\Source\ProductType $productType
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        ProductType $productType,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    )
    {
        $this->_productType = $productType;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Mageplaza\Productslider\Model\Slider $slider */
        $slider = $this->_coreRegistry->registry('mageplaza_productslider_slider');
        $this->getRequest()->setParam('slider_products', '12');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slider_');
        $form->setFieldNameSuffix('slider');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Slider Type & Position'),
                'class'  => 'fieldset-wide'
            ]
        );
        $fieldset->addField('product_type', 'select', [
            'name'   => 'product_type',
            'label'  => __('Type'),
            'title'  => __('Type'),
            'values' => $this->_productType->toOptionArray()
        ]);
        $fieldset->addField('categories_ids', '\Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Type\Renderer\Category', [
                'name'  => 'categories_ids',
                'label' => __('Categories'),
                'title' => __('Categories'),
            ]
        );

        $form->setValues($slider->getData());
        $this->setForm($form);
        $this->setData('products', '12&13');

        return parent::_prepareForm();
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Slider Type');
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHidden()
    {
        return false;
    }
}
