<?php
namespace Mageplaza\Productslider\Block;
/**
 * Class FeaturedProducts
 * @package Mageplaza\Productslider\Block
 */
class WishlistProducts extends \Mageplaza\Productslider\Block\AbstractSlider
{
	/**
	 * get collection of wishlist products
	 * @return mixed
	 */
	public function getProductCollection()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customer = $objectManager->create('\Magento\Customer\Model\Session');
		$collection = $objectManager->create('\Magento\Wishlist\Model\ResourceModel\Item\Collection');
		$collection->addCustomerIdFilter($customer->getCustomerId())->setPageSize(5);
		return $collection;
	}

	public function getProductCacheKey()
	{
		return 'mageplaza_product_slider_wishlist';
	}
}

