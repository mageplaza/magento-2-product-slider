<?php
namespace Mageplaza\Productslider\Block;
/**
 * Class BestSellerProducts
 * @package Mageplaza\Productslider\Block
 */
class BestSellerProducts extends \Mageplaza\Productslider\Block\AbstractSlider
{
    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $_catalogProductTypeConfigurable;

    /**
     * BestSellerProducts constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder
     * @param \Magento\CatalogWidget\Model\Rule $rule
     * @param \Magento\Widget\Helper\Conditions $conditionsHelper
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\CatalogWidget\Model\Rule $rule,
        \Magento\Widget\Helper\Conditions $conditionsHelper,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable,
        array $data = []
    )
    {
        $this->_catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $httpContext, $sqlBuilder, $rule, $conditionsHelper, $data);
    }

    /**
	 * get collection of best-seller products
	 * @return mixed
	 */
	public function getProductCollection()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$reportCollection = $objectManager->create('\Magento\Reports\Model\ResourceModel\Report\Collection\Factory');
		$productCollection = $reportCollection->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');
        $productCollection->setPeriod('month');
        foreach ($productCollection as $product) {
            $productIds[]=$this->getProductData($product->getProductId());
        }
        $collection      = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection')->addIdFilter($productIds);
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addStoreFilter($this->getStoreId());

        if ($this->getProductsCount() > $collection->getSize()) {
            return $collection;
        } else {
            return $collection->setPageSize($this->getProductsCount());
        }
	}

    /**
     * @return string
     */
	public function getProductCacheKey()
	{
		return 'mageplaza_product_slider_bestseller';
	}

    /**
     * Get Parent Product ID
     * @param $productID
     * @return mixed
     */
    public function getProductData($id){
        $parentByChild = $this->_catalogProductTypeConfigurable->getParentIdsByChild($id);
        if(isset($parentByChild[0])){
            //set id as parent product id...
            $id = $parentByChild[0];
        }
        return $id;
    }
}
