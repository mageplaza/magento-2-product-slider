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
 * @package     Mageplaza_Core
 * @copyright   Copyright (c) Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Block;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Block\Product\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class NewProducts
 * @package Mageplaza\Productslider\Block
 */
class NewProducts extends AbstractSlider
{
    protected $_template = 'Mageplaza_Productslider::productslider.phtml';
    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var Visibility
     */
    protected $catalogProductVisibility;

    /**
     * NewProducts constructor.
     * @param StoreManagerInterface $storeManager
     * @param DateTime $getDayDate
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        DateTime $getDayDate,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
		\Mageplaza\Productslider\Helper\Data $helperData,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($storeManager, $getDayDate, $helperData, $context, $data);

        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
    }

    /**
     * Get New product collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        $visibleProducts = $this->catalogProductVisibility->getVisibleInCatalogIds();
        $collection = $this->productCollectionFactory->create()->setVisibility($visibleProducts);
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addAttributeToFilter(
                'news_from_date',
                ['date' => true, 'to' => $this->getEndOfDayDate()],
                'left')
            ->addAttributeToFilter(
                'news_to_date',
                [
                    'or' => [
                        0 => ['date' => true, 'from' => $this->getStartOfDayDate()],
                        1 => ['is' => new \Zend_Db_Expr('null')],
                    ]
                ],
                'left')
            ->addAttributeToSort(
                'news_from_date',
                'desc')
            ->addStoreFilter($this->getStoreId())
            ->setPageSize($this->getProductsCount());

        return $collection;
    }


    public function getProductCacheKey()
    {
        return 'mageplaza_product_slider_newproducts';
    }

}