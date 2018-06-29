<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
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
 * @copyright   Copyright (c) 2017-2018 Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Block;

/**
 * Class FeaturedProducts
 * @package Mageplaza\Productslider\Block
 */
class FeaturedProducts extends AbstractSlider
{
	/**
	 * get collection of feature products
	 * @return mixed
	 */
	public function getProductCollection()
	{
		return $this->getFeaturedProductsCollection();
	}

	/**
	 * @return string
	 */
	public function getProductCacheKey()
	{
		return 'mageplaza_product_slider_featured';
	}

}
