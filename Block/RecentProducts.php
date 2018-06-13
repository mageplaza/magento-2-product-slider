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

use \Magento\Reports\Block\Product\Viewed;

/**
 * Class RecentProducts
 * @package Mageplaza\Productslider\Block
 */
class RecentProducts extends Viewed
{
    /**
     * Get Collection Recently Viewed product
     * @return mixed
     */
	public function getProductCollection()
	{
		return $this->getRecentlyViewedProducts();
	}

	public function getProductCacheKey()
	{
	    return 'mageplaza_product_slider_recent_products' ;
	}

}