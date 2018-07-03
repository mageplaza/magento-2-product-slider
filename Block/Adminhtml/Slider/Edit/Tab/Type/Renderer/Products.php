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

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Type\Renderer;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Mageplaza\Productslider\Helper\Data as HeplerData;
use Magento\Backend\Helper\Data;
use Mageplaza\Productslider\Model\SliderFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

/**
 * Class Product
 * @package Mageplaza\Blog\Block\Adminhtml\Post\Edit\Tab
 */
class Products extends Extended
{
	/**
	 * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
	 */
	protected $_productCollectionFactory;

	/**
	 * @var \Mageplaza\Productslider\Model\SliderFactory
	 */
	protected $_sliderFactory;

	/**
	 * @var \Mageplaza\Productslider\Helper\Data
	 */
	protected $_helperData;

	/**
	 * Products constructor.
	 * @param \Mageplaza\Productslider\Helper\Data $helperData
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Backend\Helper\Data $backendHelper
	 * @param \Mageplaza\Productslider\Model\SliderFactory $sliderFactory
	 * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
	 * @param array $data
	 */
	public function __construct(
		HeplerData $helperData,
		Context $context,
		Data $backendHelper,
		SliderFactory $sliderFactory,
		CollectionFactory $productCollectionFactory,
		array $data = []
	)
	{
		parent::__construct($context, $backendHelper, $data);

		$this->_helperData               = $helperData;
		$this->_sliderFactory            = $sliderFactory;
		$this->_productCollectionFactory = $productCollectionFactory;
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
		$collection = $this->_productCollectionFactory->create();
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
				'type'             => 'checkbox',
				'name'             => 'in_product',
				'align'            => 'center',
				'index'            => 'entity_id',
				'values'           => $this->_getSelectedProducts(),
			]
		);
		$this->addColumn(
			'entity_id',
			[
				'header'           => __('Product ID'),
				'type'             => 'number',
				'index'            => 'entity_id',
				'header_css_class' => 'col-id',
				'column_css_class' => 'col-id',
			]
		);
		$this->addColumn(
			'name',
			[
				'header' => __('Name'),
				'index'  => 'name',
				'class'  => 'xxx',
				'width'  => '50px',
			]
		);
		$this->addColumn(
			'sku',
			[
				'header' => __('Sku'),
				'index'  => 'sku',
				'class'  => 'xxx',
				'width'  => '50px',
			]
		);
		$this->addColumn(
			'price',
			[
				'header' => __('Price'),
				'type'   => 'currency',
				'index'  => 'price',
				'width'  => '50px',
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

	/**
	 * @return mixed
	 */
	protected function _getSelectedProducts()
	{
		$slider     = $this->getSlider();
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
		$slider   = $this->getSlider();
		$selected = $this->_helperData->unserialize($slider->getProductIds());

		if (!is_array($selected)) {
			$selected = [];
		}

		return $selected;
	}

	/**
	 * @return \Mageplaza\Productslider\Model\Slider
	 */
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
