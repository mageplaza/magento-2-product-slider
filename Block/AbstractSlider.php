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

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Block\Product\Context;

class AbstractSlider extends AbstractProduct implements BlockInterface
{
    /**
     * Default value for products count that will be shown
     */
    const DEFAULT_PRODUCTS_COUNT = 5;

    protected $_getDayDate;

    protected $_storeManager;

    protected $_slider;

    protected $_displayTypes;

    protected $_helperData;

    public $productCacheKey = 'mageplaza_product_slider_cache';

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $getDayDate,
        \Mageplaza\Productslider\Helper\Data $helperData,
        Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->_getDayDate = $getDayDate;
        $this->_storeManager = $storeManager;
        $this->_helperData = $helperData;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setData('cache_lifetime',20);
        $this->addColumnCountLayoutDepend('empty', 6)
            ->addColumnCountLayoutDepend('1column', 5)
            ->addColumnCountLayoutDepend('2columns-left', 4)
            ->addColumnCountLayoutDepend('2columns-right', 4)
            ->addColumnCountLayoutDepend('3columns', 3);
        $this->addData([
            'cache_lifetime' => $this->getCacheLifetime(),
            'cache_tags'     => [\Magento\Catalog\Model\Product::CACHE_TAG,],
            'cache_key'      => $this->getProductCacheKey(),
        ]);
    }

    public function getProductCacheKey()
    {
        return $this->productCacheKey;
    }

    public function getStartOfDayDate()
    {
        return $this->_getDayDate->date(null, '0:0:0');
    }

    public function getEndOfDayDate()
    {
        return $this->_getDayDate->date(null, '23:59:59');
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getProductsCount()
    {
        if ($this->hasData('products_count')) {
            return $this->getData('products_count');
        }

        if (null === $this->getData('products_count')) {
            $this->setData('products_count', self::DEFAULT_PRODUCTS_COUNT);
        }

        return $this->getData('products_count');
    }


    /**
     * @param $type
     * @return bool
     */
    public function getShowList($type)
    {
        $this->_slider = $this->getSlider();
        if (is_null($this->_displayTypes)) {
            if ($this->_slider['display_additional']) {
                try {
                    $this->_displayTypes = $this->_helperData->unserialize($this->_slider['display_additional']);
                } catch (\Exception $e) {
                    $this->_displayTypes = [];
                }
            } else {
                $this->_displayTypes = [];
            }
        }

        return in_array($type, $this->_displayTypes);
    }

    public function getTitle(){
        return $this->getSlider()->getName();
    }

    /**
     * @return string
     * @throws \Zend_Serializer_Exception
     */
    public function getAllOptions(){
        $sliderOptions = '';
        $allConfig = $this->_helperData->getModuleConfig('slider_design');

        foreach($allConfig as $key => $value){
            if($key == 'item_slider'){
                $sliderOptions = $sliderOptions . $this->getResponsiveConfig();

            } else if($key != 'responsive'){
                $sliderOptions = $sliderOptions . $key . ':' . $value . ',';
            }
        }

        return '{' . $sliderOptions . '}';
    }

    /**
     * @return string
     * @throws \Zend_Serializer_Exception
     */
    public function getResponsiveConfig()
    {
        $slider = $this->getSlider();
        $responsiveOptions = '';
        $inSliderResponsiveConfig = $this->_helperData->unserialize($slider->getResponsiveItems());
        $responsiveConfig = $this->_helperData->unserialize($this->_helperData->getModuleConfig('slider_design/item_slider'));
        $config = (count($inSliderResponsiveConfig) && $slider->getIsResponsive()) ? $inSliderResponsiveConfig : $responsiveConfig;

//        var_dump($config);

        foreach ($config as $value){
            $responsiveOptions = $responsiveOptions . $value['col_1'] . ':{items:' . $value['col_2'] . '},';
        }

        $responsiveOptions = rtrim($responsiveOptions,',');

        return  'responsive:{' . $responsiveOptions . '}';
    }

}