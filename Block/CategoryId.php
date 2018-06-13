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
 * @package     Mageplaza_Core
 * @copyright   Copyright (c) Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Block;

use Magento\Catalog\Block\Product\Context;

class CategoryId extends \Mageplaza\Productslider\Block\AbstractSlider
{
	/**
	 * default category id
	 */
	const DEFAULT_CATEGORY = 2;

	protected $category;

	public function __construct(
        \Magento\Catalog\Model\Category $category,
	    \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $getDayDate,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($storeManager, $getDayDate, $context, $data);

        $this->category = $category;
    }

//	protected function _construct()
//	{
//		$this->productCacheKey = $this->getProductCacheKey();
//		parent::_construct();
//	}

	public function getProductCategoryId()
	{
		return $this->getData('category_id') ? $this->getData('category_id') : self::DEFAULT_CATEGORY;
	}

	public function getProductCollection()
	{
		$category   = $this->category->load($this->getProductCategoryId());
		$collection = $category->getProductCollection()->setPageSize($this->getProductsCount())->addAttributeToSelect('*');

		return $collection;
	}

	public function getProductCacheKey()
	{
		return 'mageplaza_product_slider_category_' . $this->getData('category_id');
	}

}