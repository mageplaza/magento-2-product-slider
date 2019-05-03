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

/**
 * Class CustomProducts
 * @package Mageplaza\Productslider\Block
 */
class CustomProducts extends AbstractSlider
{
    /**
     * @return $this|mixed
     */
    public function getProductCollection()
    {
        $productIds = $this->getSlider()->getProductIds();
        if (!is_array($productIds)) {
            $productIds = explode('&', $productIds);
        }

        $collection = $this->_productCollectionFactory->create()
            ->addIdFilter($productIds)
            ->setPageSize($this->getProductsCount());
        $this->_addProductAttributesAndPrices($collection);

        return $collection;
    }
}
