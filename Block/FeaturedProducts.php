<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
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
 * @copyright   Copyright (c) 2017-2018 Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Block;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Mageplaza\Productslider\Helper\Data;

/**
 * Class FeaturedProducts
 * @package Mageplaza\Productslider\Block
 */
class FeaturedProducts extends AbstractSlider
{
    /**
     * @var Visibility
     */
    protected $catalogProductVisibility;

    /**
     * @var Collection
     */
    protected $productCollection;

    /**
     * FeaturedProducts constructor.
     * @param Visibility $catalogProductVisibility
     * @param Collection $productCollection
     * @param StoreManagerInterface $storeManager
     * @param DateTime $getDayDate
     * @param Data $helperData
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Visibility $catalogProductVisibility,
        Collection $productCollection,
        StoreManagerInterface $storeManager,
        DateTime $getDayDate,
        Data $helperData,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($storeManager, $getDayDate, $helperData, $context, $data);

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
