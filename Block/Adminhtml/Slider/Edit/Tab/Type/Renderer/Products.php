<?php

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Type\Renderer;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Registry;

/**
 * Class Product
 * @package Mageplaza\Blog\Block\Adminhtml\Post\Edit\Tab
 */
class Products extends Extended
{
	/**
	 * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
	 */
	protected $productCollectionFactory;

	/**
	 * @var \Mageplaza\Productslider\Model\SliderFactory
	 */
	protected $_sliderFactory;

	/**
	 * @var  \Magento\Framework\Registry
	 */
	protected $registry;

	protected $_objectManager = null;

	protected $_helperData;

	/**
	 * Products constructor.
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Backend\Helper\Data $backendHelper
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 * @param \Mageplaza\Productslider\Model\SliderFactory $sliderFactory
	 * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
	 * @param array $data
	 */
	public function __construct(
		\Mageplaza\Productslider\Helper\Data $helperData,
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Backend\Helper\Data $backendHelper,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Mageplaza\Productslider\Model\SliderFactory $sliderFactory,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		array $data = []
	) {
		$this->_helperData = $helperData;
		$this->_sliderFactory = $sliderFactory;
		$this->productCollectionFactory = $productCollectionFactory;
		$this->_objectManager = $objectManager;
		$this->registry = $registry;
		parent::__construct($context, $backendHelper, $data);
	}

	/**
	 * _construct
	 * @return void
	 */
	protected function _construct()
	{
		parent::_construct();
		$this->setId('productsGrid');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
		if ($this->getRequest()->getParam('slider_id')) {
			$this->setDefaultFilter(array('in_product' => 1));
		}
	}

	/**
	 * add Column Filter To Collection
	 */
	protected function _addColumnFilterToCollection($column)
	{
		if ($column->getId() == 'in_product') {
			$productIds = $this->_getSelectedProducts();

			if (empty($productIds)) {
				$productIds = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
			} else {
				if ($productIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
				}
			}
		} else {
			parent::_addColumnFilterToCollection($column);
		}

		return $this;
	}

	/**
	 * prepare collection
	 */
	protected function _prepareCollection()
	{
		$collection = $this->productCollectionFactory->create();
		$collection->addAttributeToSelect('name');
		$collection->addAttributeToSelect('sku');
		$collection->addAttributeToSelect('price');
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	/**
	 * @return $this
	 */
	protected function _prepareColumns()
	{
		$this->addColumn(
			'in_product',
			[
				'header_css_class' => 'a-center',
				'type' => 'checkbox',
				'name' => 'in_product',
				'align' => 'center',
				'index' => 'entity_id',
				'values' => $this->_getSelectedProducts(),
			]
		);
		$this->addColumn(
			'entity_id',
			[
				'header' => __('Product ID'),
				'type' => 'number',
				'index' => 'entity_id',
				'header_css_class' => 'col-id',
				'column_css_class' => 'col-id',
			]
		);
		$this->addColumn(
			'name',
			[
				'header' => __('Name'),
				'index' => 'name',
				'class' => 'xxx',
				'width' => '50px',
			]
		);
		$this->addColumn(
			'sku',
			[
				'header' => __('Sku'),
				'index' => 'sku',
				'class' => 'xxx',
				'width' => '50px',
			]
		);
		$this->addColumn(
			'price',
			[
				'header' => __('Price'),
				'type' => 'currency',
				'index' => 'price',
				'width' => '50px',
			]
		);

		return parent::_prepareColumns();
	}

	/**
	 * @return string
	 */
	public function getGridUrl()
	{
		return $this->getUrl('*/*/productsgrid', ['_current' => true]);
	}

	/**
	 * @param  object $row
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return '';
	}

	protected function _getSelectedProducts()
	{
		$slider = $this->getSlider();
		$productIds = $this->_helperData->unserialize($slider->getProductIds());

		return $productIds;
	}

	/**
	 * Retrieve selected products
	 *
	 * @return array
	 */
	public function getSelectedProducts()
	{
		$slider = $this->getSlider();
		$selected = $this->_helperData->unserialize($slider->getProductIds());

		if (!is_array($selected)) {
			$selected = [];
		}
		return $selected;
	}

	protected function getSlider()
	{
		$sliderId = $this->getRequest()->getParam('slider_id');
		$slider   = $this->_sliderFactory->create();
		if ($sliderId) {
			$slider->load($sliderId);
		}

		return $slider;
	}

	/**
	 * {@inheritdoc}
	 */
	public function canShowTab()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isHidden()
	{
		return true;
	}

}
