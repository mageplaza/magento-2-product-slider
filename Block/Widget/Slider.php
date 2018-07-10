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

namespace Mageplaza\Productslider\Block\Widget;

use Mageplaza\Productslider\Block\AbstractSlider;
use Mageplaza\Productslider\Model\Config\Source\ProductTypeWidget;

/**
 * Class Slider
 * @package Mageplaza\Productslider\Block\Widget
 */
class Slider extends AbstractSlider
{
    /**
     * Path to template file.
     *
     * @var string
     */
    protected $_template = 'Mageplaza_Productslider::widget/productslider.phtml';

    /**
     * Get Product Collection by Product Type
     * @return $this|array
     */
    public function getProductCollection()
    {
        $collection = [];

        if ($this->hasData('product_type')) {
            $productType = $this->getData('product_type');

            switch ($productType) {
                case ProductTypeWidget::NEW_PRODUCTS :
                    $collection = $this->getNewProductsCollection();
                    break;
                case ProductTypeWidget::BEST_SELLER_PRODUCTS :
                    $collection = $this->getBestSellerProductsCollection();
                    break;
                case ProductTypeWidget::FEATURED_PRODUCTS :
                    $collection = $this->getFeaturedProductsCollection();
                    break;
                case ProductTypeWidget::MOSTVIEWED_PRODUCTS :
                    $collection = $this->getMostViewedProductsCollection();
                    break;
                case ProductTypeWidget::ONSALE_PRODUCTS :
                    $collection = $this->getOnSaleProductCollection();
                    break;
                case ProductTypeWidget::RECENT_PRODUCT :
                    $collection = $this->getRecentProducts();
                    break;
                case ProductTypeWidget::WISHLIST_PRODUCT :
                    $collection = $this->getWishlistProductsCollection();
                    break;
            }
        }

        return $collection;
    }

    /**
     * @return string
     */
    public function getProductCacheKey()
    {
        return 'mageplaza_product_slider_widget';
    }
}