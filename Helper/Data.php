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

namespace Mageplaza\Productslider\Helper;

use Magento\Framework\ObjectManagerInterface;
use Mageplaza\Core\Helper\AbstractData;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package Mageplaza\Productslider\Helper
 */
class Data extends AbstractData
{
	const CONFIG_MODULE_PATH = 'productslider';

	/**
	 * Data constructor.
	 * @param \Magento\Framework\App\Helper\Context $context
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 */
	public function __construct(
		Context $context,
		StoreManagerInterface $storeManager,
		ObjectManagerInterface $objectManager
	)
	{
		parent::__construct($context, $objectManager, $storeManager);
	}

}
