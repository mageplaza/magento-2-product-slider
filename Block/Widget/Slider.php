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

namespace Mageplaza\Productslider\Block\Widget;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Mageplaza\Productslider\Block\AbstractSlider;
use Mageplaza\Productslider\Helper\Data;
use Mageplaza\Productslider\Model\Config\Source\ProductType;
use Magento\Widget\Block\BlockInterface;

/**
 * Class Slider
 * @package Mageplaza\Productslider\Block\Widget
 */
class Slider extends AbstractSlider implements BlockInterface
{
    /**
     * Display products type - new products
     */
    const DISPLAY_TYPE_NEW_PRODUCTS = 'new';

    /**
     * @var ProductType
     */
    protected $productType;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serializer;

    /**
     * Slider constructor.
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param DateTime $dateTime
     * @param Data $helperData
     * @param HttpContext $httpContext
     * @param ProductType $productType
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        DateTime $dateTime,
        Data $helperData,
        HttpContext $httpContext,
        ProductType $productType,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null,
        array $data = []
    )
    {
        $this->productType = $productType;

        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $dateTime, $helperData, $httpContext, $data);
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('Mageplaza_Productslider::widget/productslider.phtml');
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductCollection()
    {
        $collection = [];

        if ($this->hasData('product_type')) {
            $productType = $this->getData('product_type');

            $collection = $this->getLayout()->createBlock($this->productType->getBlockMap($productType))
                ->getProductCollection();
            $collection->setPageSize($this->getPageSize())->setCurPage($this->getCurrentPage());
        }

        return $collection;
    }

    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array_merge(
            parent::getCacheKeyInfo(),
            [
                $this->getDisplayType(),
                intval($this->getRequest()->getParam($this->getData('page_var_name'), 1)),
                $this->serializer->serialize($this->getRequest()->getParams())
            ]
        );
    }

    /**
     * @return bool|mixed
     */
    public function getDisplayAdditional()
    {
        $display = $this->_helperData->getModuleConfig('general/display_information');
        if (!is_array($display)) {
            $display = explode(',', $display);
        }
        return $display;
    }

    public function getHelperData()
    {
        return $this->_helperData;
    }

    /**
     * Retrieve display type for products
     *
     * @return string
     */
    public function getDisplayType()
    {
        if (!$this->hasData('product_type')) {
            $this->setData('product_type', self::DISPLAY_TYPE_NEW_PRODUCTS);
        }
        return $this->getData('product_type');
    }

    /**
     * Get number of current page based on query value
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return abs((int)$this->getRequest()->getParam($this->getData('page_var_name')));
    }

    /**
     * Retrieve how many products should be displayed on page
     *
     * @return int
     */
    protected function getPageSize()
    {
        return $this->getProductsCount();
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * Retrieve all configuration options for product slider
     *
     * @return string
     * @throws \Zend_Serializer_Exception
     */
    public function getAllOptions()
    {
        return $this->_helperData->getAllOptions();
    }
}