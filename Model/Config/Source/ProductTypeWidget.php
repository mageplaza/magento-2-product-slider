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

/**
 * Class ProductTypeWidget
 * @package Mageplaza\Productslider\Model\Config\Source
 */
class ProductTypeWidget extends ProductType
{
    /**
     * @return array
     */
    public function toArray()
    {
        $options = parent::toArray();

        unset($options[self::CATEGORY]);
        unset($options[self::CUSTOM_PRODUCTS]);

        return $options;
    }
}
