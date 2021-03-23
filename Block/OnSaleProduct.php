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
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
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
        Grouped $grouped,
        Configurable $configurable,
        LayoutFactory $layoutFactory,
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
        $productCollection = $this->_productCollectionFactory->create();
        $productCollection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addStoreFilter($this->getStoreId())
            ->addAttributeToSelect('special_from_date')
            ->addAttributeToSelect('special_to_date')
            ->addAttributeToFilter('special_price', ['gt' => 0])
            ->addAttributeToSort(
                'minimal_price',
                'asc'
            )
            ->setPageSize($this->getProductsCount());

        $productCollection->getSelect()->where(
            'price_index.final_price < price_index.price'
        );

        $productIds        = $this->getProductParentIds($productCollection);
        $productCollection = $this->_productCollectionFactory->create()->addIdFilter($productIds);

        $productCollection->addAttributeToFilter('visibility', ['neq' => 1])
            ->addAttributeToFilter('status', 1)
            ->addStoreFilter($this->getStoreId())
            ->setPageSize($this->getProductsCount());
        $this->_addProductAttributesAndPrices($productCollection);

        return $productCollection;
    }
}
