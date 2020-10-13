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
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Url\EncoderInterface;
use Mageplaza\Productslider\Helper\Data;

/**
 * Class OnSaleProduct
 * @package Mageplaza\Productslider\Block
 */
class OnSaleProduct extends AbstractSlider
{
    /**
     * @var DateTime
     */
    protected $_dateTimeStore;

    /**
     * OnSaleProduct constructor.
     *
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param DateTime $dateTime
     * @param Data $helperData
     * @param HttpContext $httpContext
     * @param EncoderInterface $urlEncoder
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
        array $data = []
    ) {
        $this->_dateTimeStore = $dateTime;
        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $dateTime,
            $helperData,
            $httpContext,
            $urlEncoder,
            $data
        );
    }

    /**
     * @inheritdoc
     */
    public function getProductCollection()
    {
        $date       = strtotime($this->_dateTimeStore->gmtDate());
        $collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*');
        $productIds = [];

        foreach ($collection as $product) {
            if ($product->getTypeId() === 'configurable' && $product->getVisibility() != 1) {
                $_children = $product->getTypeInstance()->getUsedProducts($product);
                foreach ($_children as $child) {
                    $specialPrice = (float) $child->getSpecialPrice();
                    if ($specialPrice) {
                        if ($specialPrice < ((float) $child->getPrice())) {
                            $fromDate = strtotime($child->getSpecialFromDate());
                            if (!is_null($child->getSpecialToDate())) {
                                $toDate = strtotime($child->getSpecialToDate());
                                if ($toDate > $date) {
                                    $productIds[] = $product->getId();
                                }
                            } else {
                                if ($fromDate < $date) {
                                    $productIds[] = $product->getId();
                                }
                            }
                        }
                    }
                }
            } elseif ($product->getTypeId() === 'simple' && $product->getVisibility() != 1) {
                $specialPriceSp = (float) $product->getData('special_price');
                if ($specialPriceSp) {
                    if ($specialPriceSp < ((float) $product->getPrice())) {
                        $fromDateSp = strtotime($product->getSpecialFromDate());
                        if (!is_null($product->getSpecialToDate())) {
                            $toDateSp = strtotime($product->getSpecialToDate());
                            if ($toDateSp > $date) {
                                $productIds[] = $product->getId();
                            }
                        } else {
                            if ($fromDateSp < $date) {
                                $productIds[] = $product->getId();
                            }
                        }
                    }
                }
            }
        }

        $collectionClone = $this->_productCollectionFactory->create()->addIdFilter($productIds);
        $collectionClone->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addStoreFilter($this->getStoreId())->setPageSize($this->getProductsCount());

        return $collectionClone;
    }
}
