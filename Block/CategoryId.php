<?php
namespace Mageplaza\Productslider\Block;

class CategoryId extends \Mageplaza\Productslider\Block\AbstractSlider
{
	/**
	 * default category id
	 */
	const DEFAULT_CATEGORY = 2;

	/**
	 * Get category id from layout
	 * @return int|mixed
	 */

	/**
	 * {@inheritdoc}
	 */
	protected function _construct()
	{
		$this->productCacheKey = $this->getProductCacheKey();
		parent::_construct();
	}
	public function getProductCategoryId()
	{
		return $this->getData('category_id') ? $this->getData('category_id') : self::DEFAULT_CATEGORY;
	}

	/**
	 * Get product collection by category id
	 * @return mixed
	 */
	public function getProductCollection()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$category   = $objectManager->create('Magento\Catalog\Model\Category')->load($this->getProductCategoryId());
		$collection = $category->getProductCollection()->setPageSize($this->getProductsCount())->addAttributeToSelect('*');

		return $collection;
	}

	public function getProductCacheKey()
	{
		return 'mageplaza_product_slider_category_' . $this->getData('category_id');
	}

}