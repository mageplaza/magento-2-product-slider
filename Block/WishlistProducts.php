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
		$collection=$collection->addCustomerIdFilter($customer->getCustomerId());
		foreach ($collection as $product){
			$products[]=$product->getProductId();
		}
		$collection1      = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection')->addIdFilter($products);
		$collection1      = $this->_addProductAttributesAndPrices($collection1)->addStoreFilter($this->getStoreId())//->setPageSize($this->getProductsCount()
		;
		return $collection1;
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

