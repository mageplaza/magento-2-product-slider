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
 * @copyright   Copyright (c) Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Block;

use Magento\Catalog\Block\Product\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\CategoryFactory;
use Mageplaza\Productslider\Helper\Data;

class CategoryId extends AbstractSlider
{
    /**
     * Default category id
     */
    const DEFAULT_CATEGORY = 2;

    /**
     * @var \Mageplaza\Productslider\Model\SliderFactory
     */
    protected $_sliderFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * CategoryId constructor.
     * @param StoreManagerInterface $storeManager
     * @param DateTime $getDayDate
     * @param CollectionFactory $productCollectionFactory
     * @param CategoryFactory $categoryFactory
     * @param Data $helperData
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        DateTime $getDayDate,
        CollectionFactory $productCollectionFactory,
        CategoryFactory $categoryFactory,
        Data $helperData,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($storeManager, $getDayDate, $helperData, $context, $data);

        $this->_categoryFactory = $categoryFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
    }

//	protected function _construct()
//	{
//		$this->productCacheKey = $this->getProductCacheKey();
//		parent::_construct();
//	}

    public function getProductCollection()
    {
        $productIds = $this->getProductIds();
        $collection = [];
        if (!empty($productIds)) {
            $collection = $this->_productCollectionFactory->create()->addIdFilter($productIds);
            $this->_addProductAttributesAndPrices($collection);
        }

        return $collection;
    }

	public function getProductIds()
	{
		$productIds = [];
		$catIds = $this->getSliderCategoryIds();
		foreach ($catIds as $catId) {
			$category = $this->_categoryFactory->create()->load($catId);
			$collection = $this->_productCollectionFactory->create()
				->addAttributeToSelect('*')
				->addCategoryFilter($category);

			foreach ($collection as $item) {
				$productIds[] = $item->getData('entity_id');
			}
		}

		return $productIds;
	}

	public function getSliderCategoryIds()
	{
		if ($this->getData('category_id')) {
			return $this->getData('category_id');
		}
		if ($this->getSlider()) {
			$catIds = explode(',', $this->getSlider()->getCategoriesIds());

			return $catIds;
		}

		return self::DEFAULT_CATEGORY;
	}

    public function getProductCacheKey()
    {
        return 'mageplaza_product_slider_category_' . $this->getData('category_id');
    }

}