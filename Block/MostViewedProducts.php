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

use Magento\Catalog\Block\Product\Context;

class MostViewedProducts extends AbstractSlider
{
    protected $_productsFactory;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $getDayDate,
        Context $context,
        \Magento\Reports\Model\ResourceModel\Product\Collection $productsFactory,
        array $data = []
    )
    {
        parent::__construct($storeManager, $getDayDate, $context, $data);

        $this->_productsFactory = $productsFactory;
    }

    public function getProductCollection() {
        $collection = $this->_productsFactory
            ->addAttributeToSelect(
                '*'
            )->addViewsCount()->setStoreId(
                $this->getStoreId()
            )->addStoreFilter(
                $this->getStoreId()
            )->setPageSize($this->getProductsCount());

            return $collection;
    }

    public function getProductCacheKey()
    {
        return 'mageplaza_product_slider_mostviewed';
    }

}