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

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Block\Product\Context;

class AbstractSlider extends AbstractProduct implements BlockInterface
{
    /**
     * Default value for products count that will be shown
     */
    const DEFAULT_PRODUCTS_COUNT = 5;

    protected $_getDayDate;

    protected $_storeManager;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $getDayDate,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->_getDayDate = $getDayDate;
        $this->_storeManager = $storeManager;
    }

    public function getStartOfDayDate()
    {
        return $this->_getDayDate->date(null, '0:0:0');
    }

    public function getEndOfDayDate()
    {
        return $this->_getDayDate->date(null, '23:59:59');
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getProductsCount()
    {
        if ($this->hasData('products_count')) {
            return $this->getData('products_count');
        }

        if (null === $this->getData('products_count')) {
            $this->setData('products_count', self::DEFAULT_PRODUCTS_COUNT);
        }

        return $this->getData('products_count');
    }
}