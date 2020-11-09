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

use Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Renderer\Category;
use Mageplaza\Productslider\Model\Config\Source\Location;
use Mageplaza\Productslider\Model\Config\Source\ProductType;
use Mageplaza\Productslider\Model\ResourceModel\SliderFactory;
use Mageplaza\Productslider\Model\Slider;

/**
 * Class General
 * @package Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab
 */
class General extends Generic implements TabInterface
{
    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @var SliderFactory
     */
    protected $_resourceModelSliderFactory;

    /**
     * @var GroupRepositoryInterface
     */
    protected $_groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @var DataObject
     */
    protected $_objectConverter;

    /**
     * @var Location
     */
    protected $_location;

    /**
     * @var ProductType
     */
    protected $_productType;

    /**
     * General constructor.
     *
     * @param SliderFactory $resourceModelSliderFactory
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DataObject $objectConverter
     * @param Location $location
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param ProductType $productType
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
        ProductType $productType,
        array $data = []
    ) {
        $this->_resourceModelSliderFactory = $resourceModelSliderFactory;
        $this->_groupRepository = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_objectConverter = $objectConverter;
        $this->_systemStore = $systemStore;
        $this->_location = $location;
        $this->_productType = $productType;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var Slider $slider */
        $slider = $this->_coreRegistry->registry('mageplaza_productslider_slider');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slider_');
        $form->setFieldNameSuffix('slider');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General Information'),
            'class' => 'fieldset-wide'
        ]);

        $fieldset->addField('name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'title' => __('Name'),
            'required' => true,
        ]);

        $fieldset->addField('status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'title' => __('Status'),
            'required' => true,
            'options' => [
                '1' => __('Enable'),
                '0' => __('Disable')
            ]
        ]);
        if (!$slider->getId()) {
            $slider->setData('status', 1);
        }

        $fieldset->addField('location', 'select', [
            'name' => 'location',
            'label' => __('Position'),
            'title' => __('Position'),
            'values' => $this->_location->toOptionArray()
        ]);

        $productType = $fieldset->addField('product_type', 'select', [
            'name' => 'product_type',
            'label' => __('Type'),
            'title' => __('Type'),
            'values' => $this->_productType->toOptionArray()
        ]);

        $categoryIds = $fieldset->addField('categories_ids', Category::class, [
            'name' => 'categories_ids',
            'label' => __('Categories'),
            'title' => __('Categories')
        ]);

        if (!$this->_storeManager->isSingleStoreMode()) {
            /** @var RendererInterface $rendererBlock */
            $rendererBlock = $this->getLayout()->createBlock(Element::class);
            $fieldset->addField('store_ids', 'multiselect', [
                'name' => 'store_ids',
                'label' => __('Store Views'),
                'title' => __('Store Views'),
                'required' => true,
                'values' => $this->_systemStore->getStoreValuesForForm(false, true)
            ])->setRenderer($rendererBlock);
        } else {
            $fieldset->addField('store_ids', 'hidden', [
                'name' => 'store_ids',
                'value' => $this->_storeManager->getStore()->getId()
            ]);
        }

        $customerGroups = $this->_groupRepository->getList($this->_searchCriteriaBuilder->create())->getItems();
        $fieldset->addField('customer_group_ids', 'multiselect', [
            'name' => 'customer_group_ids[]',
            'label' => __('Customer Groups'),
            'title' => __('Customer Groups'),
            'required' => true,
            'values' => $this->_objectConverter->toOptionArray($customerGroups, 'id', 'code'),
            'note' => __('Select customer group(s) to display the block to')
        ]);

        $fieldset->addField('time_cache', 'text', [
            'name' => 'time_cache',
            'label' => __('Cache Lifetime'),
            'title' => __('Cache Lifetime'),
            'class' => 'validate-digits',
            'note' => __('seconds. 86400 by default, if not set. To refresh instantly, clear the Blocks HTML Output cache.')
        ]);

        $fieldset->addField('from_date', 'date', [
            'name' => 'from_date',
            'label' => __('From Date'),
            'title' => __('From'),
            'date_format' => 'M/d/yyyy',
            'timezone' => false
        ]);

        $fieldset->addField('to_date', 'date', [
            'name' => 'to_date',
            'label' => __('To Date'),
            'title' => __('To'),
            'date_format' => 'M/d/yyyy',
            'timezone' => false
        ]);

        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(Dependence::class)
                ->addFieldMap($productType->getHtmlId(), $productType->getName())
                ->addFieldMap($categoryIds->getHtmlId(), $categoryIds->getName())
                ->addFieldDependence($categoryIds->getName(), $productType->getName(), ProductType::CATEGORY)
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
