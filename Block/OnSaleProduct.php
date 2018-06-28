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


use Magento\Catalog\Block\Product\Context;

class OnSaleProduct extends \Mageplaza\Productslider\Block\AbstractSlider
{
    protected $_helper;

    protected $catalogProductVisibility;

    protected $productCollectionFactory;

    public function __construct
    (
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Mageplaza\Productslider\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $getDayDate,
		\Mageplaza\Productslider\Helper\Data $helperData,
        Context $context,
        array $data = [])
    {
        parent::__construct($storeManager, $getDayDate, $helperData, $context, $data);

        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->_helper = $helper;
    }

    public function getProductCollection()
    {
        $visibleProducts = $this->catalogProductVisibility->getVisibleInCatalogIds();
        $collection = $this->productCollectionFactory->create()->setVisibility($visibleProducts);
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addAttributeToFilter(
                'special_from_date',
                ['date' => true, 'to' => $this->getEndOfDayDate()], 'left'
            )->addAttributeToFilter(
                'special_to_date', ['or' => [0 => ['date' => true,
                'from' => $this->getStartOfDayDate()],
                1 => ['is' => new \Zend_Db_Expr(
                    'null'
                )],]], 'left'
            )->addAttributeToSort(
                'news_from_date', 'desc'
            )->addStoreFilter($this->getStoreId())->setPageSize(
                $this->getProductsCount()
            );

        return $collection;
    }

    public function getProductCacheKey()
    {
        return 'mageplaza_product_slider_onsales';
    }

}