<?php
namespace Mageplaza\Productslider\Block;
/**
 * Class FeaturedProducts
 * @package Mageplaza\Productslider\Block
 */
class BestSellerProducts extends \Mageplaza\Productslider\Block\AbstractSlider
{
	/**
	 * get collection of feature products
	 * @return mixed
	 */
	public function getProductCollection()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$re = $objectManager->create('\Magento\Reports\Model\ResourceModel\Report\Collection\Factory');
		$resourceCollection = $re->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');
		$resourceCollection->setPageSize($this->getProductsCount());
		return $resourceCollection;
	}

	public function getProductCacheKey()
	{
		return 'mageplaza_product_slider_bestseller';
	}


}
