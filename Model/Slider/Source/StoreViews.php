<?php
/**
 * Mageplaza_Productslider extension
 *                     NOTICE OF LICENSE
 * 
 *                     This source file is subject to the MIT License
 *                     that is bundled with this package in the file LICENSE.txt.
 *                     It is also available through the world-wide-web at this URL:
 *                     https://www.mageplaza.com/LICENSE.txt
 * 
 *                     @category  Mageplaza
 *                     @package   Mageplaza_Productslider
 *                     @copyright Copyright (c) 2016
 *                     @license   https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\Productslider\Model\Slider\Source;

class StoreViews implements \Magento\Framework\Option\ArrayInterface
{
    const STORE_1 = 1;
    const STORE_2 = 2;
    const _EMPTY = 3;


    /**
     * to option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::STORE_1,
                'label' => __('Store 1')
            ],
            [
                'value' => self::STORE_2,
                'label' => __('Store 2')
            ],
            [
                'value' => self::_EMPTY,
                'label' => __('')
            ],
        ];
        return $options;

    }
}
