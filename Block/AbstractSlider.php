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
 * @package     Mageplaza_Productslider
 * @copyright   Copyright (c) Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Block;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Reports\Block\Product\Viewed;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection;
use Magento\Widget\Block\BlockInterface;
use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory as WishlistCollection;
use Mageplaza\Productslider\Helper\Data;
use Mageplaza\Productslider\Model\ResourceModel\Report\Product\CollectionFactory as MostViewedCollectionFactory;

/**
 * Class AbstractSlider
 * @package Mageplaza\Productslider\Block
 */
class AbstractSlider extends AbstractProduct implements BlockInterface
{
    const DEFAULT_PRODUCTS_COUNT = 5;
    const DEFAULT_CATEGORY       = 2;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_getDayDate;

    /**
     * @var \Mageplaza\Productslider\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customer;

    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection
     */
    protected $_bestSellersCollection;

    /**
     * @var \Magento\Wishlist\Model\ResourceModel\Item\Collection
     */
    protected $_wishlistCollectionFactory;

    /**
     * @var \Mageplaza\Productslider\Model\ResourceModel\Report\Product\CollectionFactory
     */
    protected $_mostViewedProductsFactory;

    /**
     * @var \Magento\Reports\Block\Product\Viewed
     */
    protected $_viewed;

    /**
     * AbstractSlider constructor.
     * @param CollectionFactory $productCollectionFactory
     * @param MostViewedCollectionFactory $mostViewedProductsFactory
     * @param Visibility $catalogProductVisibility
     * @param CategoryFactory $categoryFactory
     * @param Session $customer
     * @param Collection $bestSellersCollection
     * @param WishlistCollection $wishlistCollectionFactory
     * @param Viewed $viewed
     * @param DateTime $getDayDate
     * @param Data $helperData
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        MostViewedCollectionFactory $mostViewedProductsFactory,
        Visibility $catalogProductVisibility,
        CategoryFactory $categoryFactory,
        Session $customer,
        Collection $bestSellersCollection,
        WishlistCollection $wishlistCollectionFactory,
        Viewed $viewed,
        DateTime $getDayDate,
        Data $helperData,
        Context $context,
        array $data = []
    )
    {
        $this->_mostViewedProductsFactory = $mostViewedProductsFactory;
        $this->_productCollectionFactory  = $productCollectionFactory;
        $this->_catalogProductVisibility  = $catalogProductVisibility;
        $this->_categoryFactory           = $categoryFactory;
        $this->_bestSellersCollection     = $bestSellersCollection;
        $this->_wishlistCollectionFactory = $wishlistCollectionFactory;
        $this->_viewed                    = $viewed;
        $this->_getDayDate                = $getDayDate;
        $this->_helperData                = $helperData;
        $this->_customer                  = $customer;

        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();

        $this->addColumnCountLayoutDepend('empty', 6)
            ->addColumnCountLayoutDepend('1column', 5)
            ->addColumnCountLayoutDepend('2columns-left', 4)
            ->addColumnCountLayoutDepend('2columns-right', 4)
            ->addColumnCountLayoutDepend('3columns', 3);
        $this->addData([
            'cache_tags' => [\Magento\Catalog\Model\Product::CACHE_TAG,],
            'cache_key'  => $this->getProductCacheKey(),
        ]);
    }

    /**
     * @return string
     */
    public function getProductCacheKey()
    {
        return 'mageplaza_product_slider_cache';
    }

    /**
     * Check show Additional Config
     *
     * @param $type
     * @return bool
     */
    public function getShowList($type)
    {
        $slider = $this->getSlider();

        if ($slider && $slider['display_additional']) {
            try {
                $displayTypes = $this->_helperData->unserialize($slider['display_additional']);
            } catch (\Exception $e) {
                $displayTypes = [];
            }
        } else {
            $displayTypes = explode(',', $this->_helperData->getModuleConfig('general/display_information'));
        }

        return in_array($type, $displayTypes);
    }

    /**
     * Get Slider Id
     * @return string
     */
    public function getSliderId()
    {
        if ($this->getSlider()) {
            return $this->getSlider()->getSliderId();
        }

        return '';
    }

    /**
     * Get Slider Title
     *
     * @return mixed|string
     */
    public function getTitle()
    {
        if ($this->getSlider()) {
            return $this->getSlider()->getTitle();
        }
        if ($this->hasData('heading')) {
            return $this->getData('heading');
        }

        return __('Product Slider');
    }

    /**
     * Get Slider Description
     *
     * @return mixed|string
     */
    public function getDescription()
    {
        if ($this->hasData('description')) {
            return $this->getData('description');
        }

        if ($this->getSlider()) {
            return $this->getSlider()->getDescription();
        }

        return '';
    }

    /**
     * @return string
     * @throws \Zend_Serializer_Exception
     */
    public function getAllOptions()
    {
        $sliderOptions = '';
        $allConfig     = $this->_helperData->getModuleConfig('slider_design');

        foreach ($allConfig as $key => $value) {
            if ($key == 'item_slider') {
                $sliderOptions = $sliderOptions . $this->getResponsiveConfig();
            } else if ($key != 'responsive') {
                $sliderOptions = $sliderOptions . $key . ':' . $value . ',';
            }
        }

        return '{' . $sliderOptions . '}';
    }

    /**
     * @return string
     * @throws \Zend_Serializer_Exception
     */
    public function getResponsiveConfig()
    {
        $slider            = $this->getSlider();
        $responsiveOptions = '';

        $responsiveConfig = $this->_helperData->unserialize($this->_helperData->getModuleConfig('slider_design/item_slider'));
        if ($slider && $slider->getIsResponsive() != 0) {
            $inSliderResponsiveConfig = $this->_helperData->unserialize($slider->getResponsiveItems());
            $config                   = (is_array($inSliderResponsiveConfig) && $slider->getIsResponsive() == 1) ? $inSliderResponsiveConfig : $responsiveConfig;
        } else if ($slider && $slider->getIsResponsive() == 0) {
            return '';
        } else {
        }

        foreach ($config as $value) {
            if ($value['col_1'] && $value['col_2']) {
                $responsiveOptions = $responsiveOptions . $value['col_1'] . ':{items:' . $value['col_2'] . '},';
            }
        }
        $responsiveOptions = rtrim($responsiveOptions, ',');

        return 'responsive:{' . $responsiveOptions . '}';
    }

    /**
     * Get Collection of New Products
     *
     * @return $this
     */
    public function getNewProductsCollection()
    {
        $visibleProducts = $this->_catalogProductVisibility->getVisibleInCatalogIds();
        $collection      = $this->_productCollectionFactory->create()->setVisibility($visibleProducts);
        $collection      = $this->_addProductAttributesAndPrices($collection)
            ->addAttributeToFilter(
                'news_from_date',
                ['date' => true, 'to' => $this->getEndOfDayDate()],
                'left')
            ->addAttributeToFilter(
                'news_to_date',
                [
                    'or' => [
                        0 => ['date' => true, 'from' => $this->getStartOfDayDate()],
                        1 => ['is' => new \Zend_Db_Expr('null')],
                    ]
                ],
                'left')
            ->addAttributeToSort(
                'news_from_date',
                'desc')
            ->addStoreFilter($this->getStoreId())
            ->setPageSize($this->getProductsCount());

        return $collection;
    }

    /**
     * Get End of Day Date
     *
     * @return string
     */
    public function getEndOfDayDate()
    {
        return $this->_getDayDate->date(null, '23:59:59');
    }

    /**
     * Get Start of Day Date
     *
     * @return string
     */
    public function getStartOfDayDate()
    {
        return $this->_getDayDate->date(null, '0:0:0');
    }

    /**
     * Get Store Id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Get Product Count is displayed
     *
     * @return mixed
     */
    public function getProductsCount()
    {
        if ($this->hasData('products_count')) {
            return $this->getData('products_count');
        }

        if ($this->getSlider()) {
            return ($this->getSlider()->getLimitNumber()) ? $this->getSlider()->getLimitNumber() : self::DEFAULT_PRODUCTS_COUNT;
        }

        if (null === $this->getData('products_count')) {
            $this->setData('products_count', self::DEFAULT_PRODUCTS_COUNT);
        }

        return $this->getData('products_count');
    }

    /**
     * Get Collection of Custom Products
     *
     * @return $this|array
     * @throws \Zend_Serializer_Exception
     */
    public function getCustomProductsCollection()
    {
        $collection = [];
        $productIds = $this->_helperData->unserialize($this->getSlider()->getProductIds());

        if (!empty($productIds)) {
            $collection = $this->_productCollectionFactory->create()->addIdFilter($productIds)->setPageSize($this->getProductsCount());
            $this->_addProductAttributesAndPrices($collection);
        }

        return $collection;
    }

    /**
     * Get Collection of BestSeller Products
     *
     * @return $this
     */
    public function getBestSellerProductsCollection()
    {
        $productIds  = [];
        $bestSellers = $this->_bestSellersCollection->setPeriod('month');

        foreach ($bestSellers as $product) {
            $productIds[] = $product->getProductId();
        }

        $collection = $this->_productCollectionFactory->create()->addIdFilter($productIds);
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addStoreFilter($this->getStoreId())->setPageSize($this->getProductsCount());

        return $collection;
    }

    /**
     * Get Collection by Category Ids
     *
     * @return $this|array
     */
    public function getCategoryIdsCollection()
    {
        $productIds = $this->getProductIdsByCategory();
        $collection = [];
        if (!empty($productIds)) {
            $collection = $this->_productCollectionFactory->create()->addIdFilter($productIds)->setPageSize($this->getProductsCount());;
            $this->_addProductAttributesAndPrices($collection);
        }

        return $collection;
    }

    /**
     * Get ProductIds by Category
     *
     * @return array
     */
    public function getProductIdsByCategory()
    {
        $productIds = [];
        $catIds     = $this->getSliderCategoryIds();
        foreach ($catIds as $catId) {
            $category   = $this->_categoryFactory->create()->load($catId);
            $collection = $this->_productCollectionFactory->create()
                ->addAttributeToSelect('*')
                ->addCategoryFilter($category);

            foreach ($collection as $item) {
                $productIds[] = $item->getData('entity_id');
            }
        }

        return $productIds;
    }

    /**
     * Get Slider CategoryIds
     *
     * @return array|int|mixed
     */
    public function getSliderCategoryIds()
    {
        if ($this->getData('category_id')) {
            return $this->getData('category_id');
        }
        if ($this->getSlider()) {
            $catIds = explode(',', $this->getSlider()->getCategoriesIds());

            return $catIds;
        }

        return self::DEFAULT_CATEGORY;
    }

    /**
     * Get Collection of Featured Products
     *
     * @return $this
     */
    public function getFeaturedProductsCollection()
    {
        $visibleProducts = $this->_catalogProductVisibility->getVisibleInCatalogIds();

        $collection = $this->_productCollectionFactory->create()->setVisibility($visibleProducts);
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addStoreFilter($this->getStoreId())
            ->setPageSize($this->getProductsCount());
        $collection->addAttributeToFilter('is_featured', '1');

        return $collection;
    }

    /**
     * Get Collection of MostViewed Products
     *
     * @return mixed
     */
    public function getMostViewedProductsCollection()
    {
        $collection = $this->_mostViewedProductsFactory->create()
            ->addAttributeToSelect('*')
            ->setStoreId($this->getStoreId())->addViewsCount()
            ->addStoreFilter($this->getStoreId())
            ->setPageSize($this->getProductsCount());

        return $collection;
    }

    /**
     * Get Collection of OnSale Products
     *
     * @return $this
     */
    public function getOnSaleProductCollection()
    {
        $visibleProducts = $this->_catalogProductVisibility->getVisibleInCatalogIds();
        $collection      = $this->_productCollectionFactory->create()->setVisibility($visibleProducts);
        $collection      = $this->_addProductAttributesAndPrices($collection)
            ->addAttributeToFilter(
                'special_from_date',
                ['date' => true, 'to' => $this->getEndOfDayDate()], 'left'
            )->addAttributeToFilter(
                'special_to_date', ['or' => [0 => ['date' => true,
                                                   'from' => $this->getStartOfDayDate()],
                                             1 => ['is' => new \Zend_Db_Expr(
                                                 'null'
                                             )],]], 'left'
            )->addAttributeToSort(
                'news_from_date', 'desc'
            )->addStoreFilter($this->getStoreId())->setPageSize(
                $this->getProductsCount()
            );

        return $collection;
    }

    /**
     * Get Collection of Wishlist Products
     *
     * @return $this|array
     */
    public function getWishlistProductsCollection()
    {
        $collection = [];

        if ($this->_customer->isLoggedIn()) {
            $wishlist   = $this->_wishlistCollectionFactory->create()->addCustomerIdFilter($this->_customer->getCustomerId());
            $productIds = null;

            foreach ($wishlist as $product) {
                $productIds[] = $product->getProductId();
            }
            $collection = $this->_productCollectionFactory->create()->addIdFilter($productIds);
            $collection = $this->_addProductAttributesAndPrices($collection)->addStoreFilter($this->getStoreId())->setPageSize($this->getProductsCount());
        }

        return $collection;
    }

    /**
     * Get Recent Collection
     *
     * @return \Magento\Reports\Model\ResourceModel\Product\Index\Collection\AbstractCollection
     */
    public function getRecentProducts()
    {
        return $this->_viewed->getItemsCollection()->setPageSize($this->getProductsCount());
    }
}