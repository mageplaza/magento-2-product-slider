<?php
/**
 * Created by PhpStorm.
 * User: tuvv
 * Date: 3/16/2018
 * Time: 1:57 PM
 */

namespace Mageplaza\Productslider\Block;


class MostViewedProducts extends \Mageplaza\Productslider\Block\AbstractSlider
{

    protected $_productsFactory;
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\CatalogWidget\Model\Rule $rule,
        \Magento\Widget\Helper\Conditions $conditionsHelper,
        \Mageplaza\ProductSlider\Model\ResourceModel\Report\Product\CollectionFactory $productsFactory,
        array $data = [])
    {
        $this->_productsFactory = $productsFactory;
        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $httpContext, $sqlBuilder, $rule, $conditionsHelper, $data);
    }


    public function getProductCollection() {
        $currentStoreId = $this->_storeManager->getStore()->getId();

        $collection = $collection = $this->_productsFactory->create()
            ->addAttributeToSelect(
                '*'
            )->addViewsCount()->setStoreId(
                $currentStoreId
            )->addStoreFilter(
                $currentStoreId
            );

        if ($this->getProductsCount() > $collection->getSize()) {
            return $collection;
        } else {
            return $collection->setPageSize($this->getProductsCount());
        }
    }

    public function getProductCacheKey()
    {
        return 'mageplaza_product_slider_mostviewed';
    }

}