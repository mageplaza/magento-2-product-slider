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

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\General;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Magento\Store\Model\System\Store;
use Mageplaza\Productslider\Model\Config\Source\Location;
use Mageplaza\Productslider\Model\ResourceModel\SliderFactory;

/**
 * Class General
 * @package Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\General
 */
class General extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Mageplaza\Productslider\Model\ResourceModel\SliderFactory
     */
    protected $_resourceModelSliderFactory;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $_groupRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Convert\DataObject
     */
    protected $_objectConverter;

    /**
     * @var \Mageplaza\Productslider\Model\Config\Source\Location
     */
    protected $_location;

    /**
     * General constructor.
     * @param \Mageplaza\Productslider\Model\ResourceModel\SliderFactory $resourceModelSliderFactory
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Convert\DataObject $objectConverter
     * @param \Mageplaza\Productslider\Model\Config\Source\Location $location
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        SliderFactory $resourceModelSliderFactory,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $objectConverter,
        Location $location,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        array $data = []
    )
    {
        $this->_resourceModelSliderFactory = $resourceModelSliderFactory;
        $this->_groupRepository            = $groupRepository;
        $this->_searchCriteriaBuilder      = $searchCriteriaBuilder;
        $this->_objectConverter            = $objectConverter;
        $this->_systemStore                = $systemStore;
        $this->_location                   = $location;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return \Magento\Backend\Block\Widget\Form\Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Mageplaza\Productslider\Model\Slider $slider */
        $slider        = $this->_coreRegistry->registry('mageplaza_productslider_slider');
        $resourceModel = $this->_resourceModelSliderFactory->create();

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slider_');
        $form->setFieldNameSuffix('slider');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('General Information'),
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
        $fieldset->addField('name', 'text', [
                'name'     => 'name',
                'label'    => __('Name'),
                'title'    => __('Name'),
                'required' => true,
            ]
        );
        $fieldset->addField('title', 'text', [
                'name'  => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
            ]
        );
        $fieldset->addField('description', 'text', [
                'name'  => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
            ]
        );
        $fieldset->addField('limit_number', 'text', [
                'name'  => 'limit_number',
                'label' => __('Limit number of products'),
                'title' => __('Limit number of products'),
            ]
        );
        $fieldset->addField('status', 'select', [
                'name'     => 'status',
                'label'    => __('Status'),
                'title'    => __('Status'),
                'required' => true,
                'options'  => [
                    '1' => __('Active'),
                    '0' => __('Inactive')
                ],
            ]
        );
        if (!$slider->getId()) {
            $slider->setData('status', 1);
        }

        if (!$this->_storeManager->isSingleStoreMode()) {
            /** @var \Magento\Framework\Data\Form\Element\Renderer\RendererInterface $rendererBlock */
            $rendererBlock = $this->getLayout()->createBlock('Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element');
            $fieldset->addField('store_ids', 'multiselect', [
                'name'     => 'store_ids',
                'label'    => __('Store Views'),
                'title'    => __('Store Views'),
                'required' => true,
                'values'   => $this->_systemStore->getStoreValuesForForm(false, true)
            ])->setRenderer($rendererBlock);
        } else {
            $fieldset->addField('store_ids', 'hidden', [
                'name'  => 'store_ids',
                'value' => $this->_storeManager->getStore()->getId()
            ]);
        }
        $slider->setData('store_ids', $resourceModel->getStoresByRuleId($slider->getId()));

        $slider->setData('customer_group_ids', $resourceModel->getCustomerGroupByRuleId($slider->getId()));
        $customerGroups = $this->_groupRepository->getList($this->_searchCriteriaBuilder->create())->getItems();
        $fieldset->addField('customer_group_ids', 'multiselect', [
                'name'     => 'customer_group_ids[]',
                'label'    => __('Customer Groups'),
                'title'    => __('Customer Groups'),
                'required' => true,
                'values'   => $this->_objectConverter->toOptionArray($customerGroups, 'id', 'code'),
                'note'     => __('Select customer group(s) to display the block to')
            ]
        );
        $fieldset->addField('location', 'select', [
            'name'   => 'location',
            'label'  => __('Position'),
            'title'  => __('Position'),
            'values' => $this->_location->toOptionArray()
        ]);
        $fieldset->addField('time_cache', 'text', [
                'name'  => 'time_cache',
                'label' => __('Cache Lifetime (Seconds)'),
                'title' => __('Cache Lifetime (Seconds)'),
                'note'  => __('86400 by default, if not set. To refresh instantly, clear the Blocks HTML Output cache.')
            ]
        );
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField('from_date', 'date', [
                'name'         => 'from_date',
                'label'        => __('From Date'),
                'title'        => __('From'),
                'input_format' => DateTime::DATE_INTERNAL_FORMAT,
                'date_format'  => $dateFormat
            ]
        );
        $fieldset->addField('to_date', 'date', [
                'name'         => 'to_date',
                'label'        => __('To Date'),
                'title'        => __('To'),
                'input_format' => DateTime::DATE_INTERNAL_FORMAT,
                'date_format'  => $dateFormat
            ]
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
        return __('General Information');
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
