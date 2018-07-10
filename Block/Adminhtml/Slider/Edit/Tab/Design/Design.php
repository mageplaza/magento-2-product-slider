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

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Design;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Mageplaza\Productslider\Helper\Data;
use Mageplaza\Productslider\Model\Config\Source\Additional;

/**
 * Class Design
 * @package Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Design
 */
class Design extends Generic implements TabInterface
{
    /**
     * Path to template file.
     *
     * @var string
     */
    protected $_template = 'slider/tab/design.phtml';

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;

    /**
     * @var \Mageplaza\Productslider\Model\Config\Source\Additional
     */
    protected $_additional;

    /**
     * @var \Mageplaza\Productslider\Helper\Data
     */
    protected $_helperData;

    /**
     * Design constructor.
     * @param \Mageplaza\Productslider\Helper\Data $helperData
     * @param \Mageplaza\Productslider\Model\Config\Source\Additional $additional
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        Data $helperData,
        Additional $additional,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    )
    {
        $this->_helperData = $helperData;
        $this->_additional = $additional;
        
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return \Magento\Backend\Block\Widget\Form\Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Serializer_Exception
     */
    protected function _prepareForm()
    {
        /** @var \Mageplaza\Productslider\Model\Slider $slider */
        $slider = $this->_coreRegistry->registry('mageplaza_productslider_slider');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slider_');
        $form->setFieldNameSuffix('slider');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Design'),
                'class'  => 'fieldset-wide'
            ]
        );
        $fieldset->addField('is_responsive', 'select', [
                'name'    => 'is_responsive',
                'label'   => __('Is Responsive'),
                'title'   => __('Is Responsive'),
                'options' => [
                    '1' => __('Yes'),
                    '0' => __('No'),
                    '2' => __('Use Config')
                ]
            ]
        );
        
        $fieldset->getAdvancedChildrenHtml();
        $tableResponsiveBlock = $this->getLayout()->createBlock('\Mageplaza\Productslider\Block\System\Config\Form\Field\Active');
        $fieldset->addField('responsive_items', 'multiselect', [
                'name'  => 'responsive_items',
                'label' => __('Max Items slider'),
                'title' => __('Max Items slider'),
            ]
        )->setRenderer($tableResponsiveBlock);
        if ($responsiveData = $slider->getResponsiveItems()) {
            $slider->setData('responsive_items', $this->_helperData->unserialize($responsiveData));
        }

        $fieldset->addField('display_additional', 'multiselect', [
                'name'   => 'display_additional',
                'label'  => __('Display additional information'),
                'title'  => __('Display additional information'),
                'values' => $this->_additional->toOptionArray(),
                'note'   => __('Select information or button(s) to display with products.')
            ]
        );
        if ($displayData = $slider->getDisplayAdditional()) {
            $slider->setData('display_additional', $this->_helperData->unserialize($displayData));
        }

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
