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

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\General;

use Magento\Store\Model\System\Store;
use Mageplaza\Productslider\Model\ResourceModel\SliderFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Stdlib\DateTime;
use Magento\Customer\Api\GroupRepositoryInterface;
use Mageplaza\Productslider\Model\Config\Source\Location;

class General extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;

    /**
     * Country options
     * 
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $_booleanOptions;


    protected $resourceModelSliderFactory;

    protected $_groupRepository;

    protected $_searchCriteriaBuilder;

    protected $_objectConverter;

    protected $location;

    /**
     * General constructor.
     * @param \Magento\Config\Model\Config\Source\Yesno $booleanOptions
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param Store $systemStore
     * @param array $data
     */
    public function __construct(
        SliderFactory $resourceModelSliderFactory,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $objectConverter,
        Location $location,
        \Magento\Config\Model\Config\Source\Yesno $booleanOptions,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Store $systemStore,
        array $data = []
    )
    {
        $this->resourceModelSliderFactory = $resourceModelSliderFactory;
        $this->_groupRepository         = $groupRepository;
        $this->_searchCriteriaBuilder   = $searchCriteriaBuilder;
        $this->_objectConverter         = $objectConverter;
        $this->systemStore              = $systemStore;
        $this->location = $location;
        $this->_booleanOptions    = $booleanOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return \Magento\Backend\Block\Widget\Form\Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Mageplaza\Productslider\Model\Slider $slider */
        $slider = $this->_coreRegistry->registry('mageplaza_productslider_slider');
        $resourceModel = $this->resourceModelSliderFactory->create();

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
                'name'  => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
            ]
        );
        $fieldset->addField('status', 'select', [
                'name'  => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'options'  => [
                    '1' => __('Active'),
                    '0' => __('Inactive')
                ],
            ]
        );
        if (!$slider->getId()) {
            $slider->setData('is_active', 1);
        }

        if (!$this->_storeManager->isSingleStoreMode()) {
            /** @var \Magento\Framework\Data\Form\Element\Renderer\RendererInterface $rendererBlock */
            $rendererBlock = $this->getLayout()->createBlock('Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element');
            $fieldset->addField('store_ids', 'multiselect', [
                'name'     => 'store_ids',
                'label'    => __('Store Views'),
                'title'    => __('Store Views'),
                'required' => true,
                'values'   => $this->systemStore->getStoreValuesForForm(false, true)
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




        $fieldset->addField('location', 'multiselect', [
            'name'     => 'location',
            'label'    => __('Position'),
            'title'    => __('Position'),
            'values'   => $this->location->toOptionArray()
        ]);




        $fieldset->addField('time_cache', 'text', [
                'name'  => 'time_cache',
                'label' => __('Cache Lifetime (Seconds)'),
                'title' => __('Cache Lifetime (Seconds)'),
                'note'     => __('86400 by default, if not set. To refresh instantly, clear the Blocks HTML Output cache.')
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
        return __('General Information');
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
