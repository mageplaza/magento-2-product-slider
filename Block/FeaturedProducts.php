<?php
namespace Mageplaza\Productslider\Block;
/**
 * Class FeaturedProducts
 * @package Mageplaza\Productslider\Block
 */
class FeaturedProducts extends \Mageplaza\Productslider\Block\AbstractSlider
{
	/**
	 * get collection of feature products
	 * @return mixed
	 */
	public function getProductCollection()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$visibleProducts = $objectManager->create('\Magento\Catalog\Model\Product\Visibility')->getVisibleInCatalogIds();
		$collection = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection')->setVisibility($visibleProducts);
		$collection->addMinimalPrice()
			->addFinalPrice()
			->addTaxPercents()
			->addAttributeToSelect('*')
			->addStoreFilter($this->getStoreId())
			->setPageSize($this->getProductsCount())
		;
		$collection->addAttributeToFilter('is_featured' , '1');
		return $collection;
	}

	public function getProductCacheKey()
	{
		return 'mageplaza_product_slider_featured' ;
	}


}
