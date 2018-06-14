<?php
/**
 * Created by PhpStorm.
 * User: nk
 * Date: 14/06/2018
 * Time: 18:16
 */

namespace Mageplaza\Productslider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Location implements ArrayInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $values = [
            ['value' => 'top-of-content', 'label' => __('Top of content')],
            ['value' => 'bottom-of-content', 'label' => __('Bottom of content')],
        ];

        $allValues = array_merge($values, [
            ['value' => 'top-of-sidebar', 'label' => __('Top of sidebar')],
            ['value' => 'bottom-of-sidebar', 'label' => __('Bottom of sidebar')]
        ]);


        $options = [
            ['value' => $allValues, 'label' => 'All Page'],
            ['value' => $values, 'label' => 'Home Page'],
            ['value' => $allValues, 'label' => 'Category Page'],
            ['value' => $values, 'label' => 'Product Page'],
            ['value' => $values, 'label' => 'Checkout Cart Page'],
        ];

        return $options;
    }
}