<?php
/**
 * Mageplaza_Productslider extension
 *                     NOTICE OF LICENSE
 * 
 *                     This source file is subject to the MIT License
 *                     that is bundled with this package in the file LICENSE.txt.
 *                     It is also available through the world-wide-web at this URL:
 *                     https://www.mageplaza.com/LICENSE.txt
 * 
 *                     @category  Mageplaza
 *                     @package   Mageplaza_Productslider
 *                     @copyright Copyright (c) 2016
 *                     @license   https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab;

class Slider extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Country options
     * 
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $_booleanOptions;

    /**
     * Store View options
     * 
     * @var \Mageplaza\Productslider\Model\Slider\Source\StoreViews
     */
    protected $_storeViewsOptions;

    /**
     * constructor
     * 
     * @param \Magento\Config\Model\Config\Source\Yesno $booleanOptions
     * @param \Mageplaza\Productslider\Model\Slider\Source\StoreViews $storeViewsOptions
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Config\Model\Config\Source\Yesno $booleanOptions,
        \Mageplaza\Productslider\Model\Slider\Source\StoreViews $storeViewsOptions,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    )
    {
        $this->_booleanOptions    = $booleanOptions;
        $this->_storeViewsOptions = $storeViewsOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
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
                'legend' => __('Slider Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        if ($slider->getId()) {
            $fieldset->addField(
                'slider_id',
                'hidden',
                ['name' => 'slider_id']
            );
        }
        $fieldset->addField(
            'name',
            'text',
            [
                'name'  => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
            ]
        );
        $fieldset->addField(
            'store_views',
            'multiselect',
            [
                'name'  => 'store_views',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'values' => $this->_storeViewsOptions->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'active_from',
            'date',
            [
                'name'  => 'active_from',
                'label' => __('Active From'),
                'title' => __('Active From'),
                'date_format' => $this->_localeDate->getDateFormat(
                    \IntlDateFormatter::SHORT
                ),
                'class' => 'validate-date',
            ]
        );
        $fieldset->addField(
            'active_to',
            'text',
            [
                'name'  => 'active_to',
                'label' => __('Active To'),
                'title' => __('Active To'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'name'  => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'values' => $this->_booleanOptions->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'serialized_data',
            'textarea',
            [
                'name'  => 'serialized_data',
                'label' => __('Data'),
                'title' => __('Data'),
            ]
        );

        $sliderData = $this->_session->getData('mageplaza_productslider_slider_data', true);
        if ($sliderData) {
            $slider->addData($sliderData);
        } else {
            if (!$slider->getId()) {
                $slider->addData($slider->getDefaultValues());
            }
        }
        $form->addValues($slider->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Slider');
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
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
