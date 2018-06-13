<?php
namespace Mageplaza\Productslider\Block;
use Magento\Catalog\Block\Product\Context;

/**
 * Class FeaturedProducts
 * @package Mageplaza\Productslider\Block
 */
class FeaturedProducts extends \Mageplaza\Productslider\Block\AbstractSlider
{
    protected $catalogProductVisibility;

    protected $productCollection;

    public function __construct(
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $getDayDate,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($storeManager, $getDayDate, $context, $data);

        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->productCollection = $productCollection;
    }

    /**
	 * get collection of feature products
	 * @return mixed
	 */
	public function getProductCollection()
	{
		$visibleProducts = $this->catalogProductVisibility->getVisibleInCatalogIds();

		$collection = $this->productCollection->setVisibility($visibleProducts);
		$collection->addMinimalPrice()
			->addFinalPrice()
			->addTaxPercents()
			->addAttributeToSelect('*')
			->addStoreFilter($this->getStoreId())
			->setPageSize($this->getProductsCount())
		;
		$collection->addAttributeToFilter('is_featured' , '1');

		return $collection;
	}

	public function getProductCacheKey()
	{
		return 'mageplaza_product_slider_featured' ;
	}


}
