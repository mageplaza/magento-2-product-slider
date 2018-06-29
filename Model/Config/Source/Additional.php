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

namespace Mageplaza\Productslider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Additional
 * @package Mageplaza\AutoRelated\Model\Config\Source
 */
class Additional implements ArrayInterface
{
	const SHOW_PRICE = 1;
	const SHOW_CART = 2;
	const SHOW_REVIEW = 3;

	/**
	 * Return array of options as value-label pairs
	 *
	 * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
	 */
	public function toOptionArray()
	{
		return [
			['value' => self::SHOW_PRICE, 'label' => __('Price')],
			['value' => self::SHOW_CART, 'label' => __('Add to cart button')],
			['value' => self::SHOW_REVIEW, 'label' => __('Review information')]
		];
	}
}
