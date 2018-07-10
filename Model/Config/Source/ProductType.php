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

namespace Mageplaza\Productslider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class ProductType
 * @package Mageplaza\Productslider\Model\Config\Source
 */
class ProductType implements ArrayInterface
{
    const NEW_PRODUCTS         = 'new';
    const BEST_SELLER_PRODUCTS = 'best-seller';
    const FEATURED_PRODUCTS    = 'featured';
    const MOSTVIEWED_PRODUCTS  = 'mostviewed';
    const ONSALE_PRODUCTS      = 'onsale';
    const RECENT_PRODUCT       = 'recent';
    const WISHLIST_PRODUCT     = 'wishlist';
    const CATEGORYID           = 'categoryId';
    const CUSTOM_PRODUCTS      = 'custom';

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [
            ['value' => self::NEW_PRODUCTS, 'label' => __('New Products')],
            ['value' => self::BEST_SELLER_PRODUCTS, 'label' => __('Best Seller Products')],
            ['value' => self::FEATURED_PRODUCTS, 'label' => __('Featured Products')],
            ['value' => self::MOSTVIEWED_PRODUCTS, 'label' => __('Most Viewed Products')],
            ['value' => self::ONSALE_PRODUCTS, 'label' => __('OnSale Products')],
            ['value' => self::RECENT_PRODUCT, 'label' => __('Recent Products')],
            ['value' => self::WISHLIST_PRODUCT, 'label' => __('WishList Products')],
            ['value' => self::CATEGORYID, 'label' => __('Select By Category')],
            ['value' => self::CUSTOM_PRODUCTS, 'label' => __('Custom Specific Products')]
        ];

        return $options;
    }
}