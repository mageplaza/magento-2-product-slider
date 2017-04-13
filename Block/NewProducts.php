<?php
namespace Mageplaza\Productslider\Block;
/**
 * Class NewProducts
 * Get collection of new products
 * @package Mageplaza\Productslider\Block
 */

class NewProducts extends \Mageplaza\Productslider\Block\AbstractSlider
{

	public function getProductCollection()
	{
		$objectManager   = \Magento\Framework\App\ObjectManager::getInstance();
		$visibleProducts = $objectManager->create('\Magento\Catalog\Model\Product\Visibility')->getVisibleInCatalogIds();
		$collection      = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection')->setVisibility($visibleProducts);
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
	{return 'mageplaza_product_slider_newproducts' ;
	}

}