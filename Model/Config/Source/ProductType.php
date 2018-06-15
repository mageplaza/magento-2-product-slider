<?php
/**
 * Created by PhpStorm.
 * User: nk
 * Date: 15/06/2018
 * Time: 14:17
 */

namespace Mageplaza\Productslider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class ProductType implements ArrayInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [
            ['value' => 'new-products', 'label' => __('New Products')],
            ['value' => 'best-seller-products', 'label' => __('Best Seller Products')],
            ['value' => 'featured-products', 'label' => __('Featured Products')],
            ['value' => 'most-viewed-products', 'label' => __('Most Viewed Products')],
            ['value' => 'onsale-products', 'label' => __('OnSale Products')],
            ['value' => 'recent-products', 'label' => __('Recent Products')],
            ['value' => 'wishlist-products', 'label' => __('WishList Products')],
            ['value' => 'category', 'label' => __('Select By Category')],
            ['value' => 'custom-products', 'label' => __('Custom Specific Products')]
        ];

        return $options;
    }
}