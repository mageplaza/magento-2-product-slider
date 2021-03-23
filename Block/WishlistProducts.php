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
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Block;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory as WishlistCollectionFactory;
use Mageplaza\Productslider\Helper\Data;

/**
 * Class FeaturedProducts
 * @package Mageplaza\Productslider\Block
 */
class WishlistProducts extends AbstractSlider
{
    /**
     * @var WishlistCollectionFactory
     */
    protected $_wishlistCollectionFactory;

    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * WishlistProducts constructor.
     *
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param DateTime $dateTime
     * @param Data $helperData
     * @param HttpContext $httpContext
     * @param EncoderInterface $urlEncoder
     * @param WishlistCollectionFactory $wishlistCollectionFactory
     * @param CustomerSession $_customerSession
     * @param Grouped $grouped
     * @param Configurable $configurable
     * @param LayoutFactory $layoutFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        DateTime $dateTime,
        Data $helperData,
        HttpContext $httpContext,
        EncoderInterface $urlEncoder,
        WishlistCollectionFactory $wishlistCollectionFactory,
        CustomerSession $_customerSession,
        Grouped $grouped,
        Configurable $configurable,
        LayoutFactory $layoutFactory,
        array $data = []
    ) {
        $this->_wishlistCollectionFactory = $wishlistCollectionFactory;
        $this->_customerSession           = $_customerSession;

        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $dateTime,
            $helperData,
            $httpContext,
            $urlEncoder,
            $grouped,
            $configurable,
            $layoutFactory,
            $data
        );
    }

    /**
     * @inheritdoc
     */
    public function getProductCollection()
    {
        $collection = [];

        if ($this->_customerSession->isLoggedIn()) {
            $wishlist = $this->_wishlistCollectionFactory->create()
                ->addCustomerIdFilter($this->_customerSession->getCustomerId());

            $mpProductIds = $this->getProductParentIds($wishlist);

            $collection = $this->_productCollectionFactory->create()->addIdFilter($mpProductIds);
            $collection = $this->_addProductAttributesAndPrices($collection)
                ->addStoreFilter($this->getStoreId())
                ->setPageSize($this->getProductsCount());
        }

        return $collection;
    }
}
