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
 * Class BestSellerProducts
 * @package Mageplaza\Productslider\Block
 */
class BestSellerProducts extends \Mageplaza\Productslider\Block\AbstractSlider
{
    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $_catalogProductTypeConfigurable;

    protected $bestSellersCollection;

    protected $productCollection;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection $bestSellersCollection,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $getDayDate,
        \Mageplaza\Productslider\Helper\Data $helperData,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($storeManager, $getDayDate, $helperData, $context, $data);

        $this->bestSellersCollection = $bestSellersCollection;
        $this->productCollection = $productCollection;
    }

    /**
     * get collection of best-seller products
     * @return mixed
     */
    public function getProductCollection()
    {
        $productIds = [];

        $bestSellers = $this->bestSellersCollection->setPeriod('month');

        foreach ($bestSellers as $product) {
            $productIds[] = $product->getProductId();
        }

        $collection = $this->productCollection->addIdFilter($productIds);
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addStoreFilter($this->getStoreId());

        if ($this->getProductsCount() > $collection->getSize()) {
            return $collection;
        } else {
            return $collection->setPageSize($this->getProductsCount());
        }
    }

    /**
     * @return string
     */
    public function getProductCacheKey()
    {
        return 'mageplaza_product_slider_bestseller';
    }

}