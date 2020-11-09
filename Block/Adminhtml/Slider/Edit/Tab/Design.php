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
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Mageplaza\Productslider\Helper\Data;
use Mageplaza\Productslider\Model\Config\Source\Additional;
use Mageplaza\Productslider\Model\Slider;

/**
 * Class Design
 * @package Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab
 */
class Design extends Generic implements TabInterface
{
    /**
     * @var Store
     */
    protected $systemStore;

    /**
     * @var Additional
     */
    protected $_additional;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * Design constructor.
     *
     * @param Data $helperData
     * @param Additional $additional
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        Data $helperData,
        Additional $additional,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    ) {
        $this->_helperData = $helperData;
        $this->_additional = $additional;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var Slider $slider */
        $slider = $this->_coreRegistry->registry('mageplaza_productslider_slider');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slider_');
        $form->setFieldNameSuffix('slider');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Design'),
                'class' => 'fieldset-wide'
            ]
        );

        $fieldset->addField('title', 'text', [
            'name' => 'title',
            'label' => __('Title'),
            'title' => __('Title'),
        ]);
        $fieldset->addField('description', 'textarea', [
            'name' => 'description',
            'label' => __('Description'),
            'title' => __('Description'),
        ]);
        $fieldset->addField('limit_number', 'text', [
            'name' => 'limit_number',
            'label' => __('Limit the number of products'),
            'title' => __('Limit the number of products'),
            'class' => 'validate-digits'
        ]);

        $fieldset->addField('display_additional', 'multiselect', [
            'name' => 'display_additional',
            'label' => __('Display additional information'),
            'title' => __('Display additional information'),
            'values' => $this->_additional->toOptionArray(),
            'note' => __('Select information or button(s) to display with products.')
        ]);

        $isResponsive = $fieldset->addField('is_responsive', 'select', [
            'name' => 'is_responsive',
            'label' => __('Is Responsive'),
            'title' => __('Is Responsive'),
            'options' => [
                '1' => __('Yes'),
                '0' => __('No'),
                '2' => __('Use Config')
            ]
        ]);

        $responsiveItem = $fieldset->addField(
            'responsive_items',
            'Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Renderer\Responsive',
            [
                'name' => 'responsive_items',
                'label' => __('Max Items slider'),
                'title' => __('Max Items slider'),
                'note' => __('Default: 3 items.')
            ]
        );

        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence')
                ->addFieldMap($isResponsive->getHtmlId(), $isResponsive->getName())
                ->addFieldMap($responsiveItem->getHtmlId(), $responsiveItem->getName())
                ->addFieldDependence($responsiveItem->getName(), $isResponsive->getName(), '1')
        );

        $form->setValues($slider->getData());
        $this->setForm($form);

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
        return __('Design');
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
