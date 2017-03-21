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
		$collection=null;
		if ($customer->isLoggedIn())
		{
			$whishlishCollection = $objectManager->create('\Magento\Wishlist\Model\ResourceModel\Item\Collection');
			$whishlishCollection=$whishlishCollection->addCustomerIdFilter($customer->getCustomerId());
			$productIds=null;
			foreach ($whishlishCollection as $product)
			{
				$productIds[]=$product->getProductId();
			}
			$collection      = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection')->addIdFilter($productIds);
			$collection     = $this->_addProductAttributesAndPrices($collection)->addStoreFilter($this->getStoreId());
		}
		return $collection;
	}

	public function getProductCacheKey()
	{
		return 'mageplaza_product_slider_wishlist';
	}

	public function getCacheLifetime()
	{
		return 0;
	}

}

