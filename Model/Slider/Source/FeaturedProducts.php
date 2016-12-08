<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageplaza\Productslider\Model\Slider\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * FeaturedProducts attribute source model
 */
class FeaturedProducts extends AbstractSource
{
	/**
	 * Retrieve option array
	 *
	 * @return array
	 */
	public function getAllOptions()
	{
		return [
			['value' => 0, 'label' => __('No')],
			['value' => 1, 'label' => __('Yes')]
		];
	}
}
