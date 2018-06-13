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

/**
 * Class FeaturedProducts
 * @package Mageplaza\Productslider\Block
 */
class WishlistProducts extends \Mageplaza\Productslider\Block\AbstractSlider
{
    protected $customer;

    protected $wishlistCollection;

    protected $productCollection;

    public function __construct(
        \Magento\Wishlist\Model\ResourceModel\Item\Collection $wishlistCollection,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Magento\Customer\Model\Session $customer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $getDayDate,
        Context $context,
        array $data = [])
    {
        parent::__construct($storeManager, $getDayDate, $context, $data);

        $this->customer = $customer;
        $this->wishlistCollection = $wishlistCollection;
        $this->productCollection = $productCollection;
    }

    /**
     * Get Collection Wishlist Product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|null
     */
    public function getProductCollection()
    {
        $collection=null;
        if ($this->customer->isLoggedIn()) {

            $wishlist = $this->wishlistCollection->addCustomerIdFilter($this->customer->getCustomerId());
            $productIds = null;

            foreach ($wishlist as $product) {
                $productIds[] = $product->getProductId();
            }

            $collection = $this->productCollection->addIdFilter($productIds);
            $collection = $this->_addProductAttributesAndPrices($collection)->addStoreFilter($this->getStoreId());
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

