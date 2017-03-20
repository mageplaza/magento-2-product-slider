<?php
namespace Mageplaza\Productslider\Block;
/**
 * Class NewProducts
 * Get collection of new products
 * @package Mageplaza\Productslider\Block
 */

class RecentProducts extends \Magento\Reports\Block\Product\Viewed
{

	public function getProductCollection()
	{
		return $this->getRecentlyViewedProducts();
	}


	public function getProductCacheKey()
	{return 'mageplaza_product_slider_recent_products' ;
	}

}