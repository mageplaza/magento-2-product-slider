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

namespace Mageplaza\Productslider\Block;

use Magento\Catalog\Block\Product\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Mageplaza\Productslider\Helper\Data;

/**
 * Class CustomProducts
 * @package Mageplaza\Productslider\Block
 */
class CustomProducts extends AbstractSlider
{
	/**
	 * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
	 */
	protected $_productCollectionFactory;

	/**
	 * CustomProducts constructor.
	 * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Magento\Framework\Stdlib\DateTime\DateTime $getDayDate
	 * @param \Mageplaza\Productslider\Helper\Data $helperData
	 * @param \Magento\Catalog\Block\Product\Context $context
	 * @param array $data
	 */
	public function __construct(
		CollectionFactory $productCollectionFactory,
		StoreManagerInterface $storeManager,
		DateTime $getDayDate,
		Data $helperData,
		Context $context,
		array $data = []
	)
	{
		parent::__construct($storeManager, $getDayDate, $helperData, $context, $data);

		$this->_productCollectionFactory = $productCollectionFactory;
	}

	/**
	 * get Product Collection of Custom Product
	 * @return $collection
	 */
	public function getProductCollection()
	{
		$productIds = $this->_helperData->unserialize($this->getSlider()->getProductIds());
		$collection = [];

		if (!empty($productIds)) {
			$collection = $this->_productCollectionFactory->create()->addIdFilter($productIds);
			$this->_addProductAttributesAndPrices($collection);
		}

		return $collection;
	}
}