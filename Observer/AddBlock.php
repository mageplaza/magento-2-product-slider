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

namespace Mageplaza\Productslider\Observer;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageplaza\Productslider\Helper\Data;
use Mageplaza\Productslider\Model\Config\Source\ProductType;
use Mageplaza\Productslider\Model\ResourceModel\SliderFactory as ResourceModelFactory;
use Mageplaza\Productslider\Model\SliderFactory;

/**
 * Class AddBlock
 * @package Mageplaza\AutoRelated\Observer
 */
class AddBlock implements ObserverInterface
{
    const NEW_PRODUCT_PATH         = 'Mageplaza\Productslider\Block\NewProducts';
    const BEST_SELLER_PATH         = 'Mageplaza\Productslider\Block\BestSellerProducts';
    const FEATURED_PRODUCTS_PATH   = 'Mageplaza\Productslider\Block\FeaturedProducts';
    const MOSTVIEWED_PRODUCTS_PATH = 'Mageplaza\Productslider\Block\MostViewedProducts';
    const ONSALE_PRODUCTS_PATH     = 'Mageplaza\Productslider\Block\OnSaleProduct';
    const RECENT_PRODUCT_PATH      = 'Mageplaza\Productslider\Block\RecentProducts';
    const WISHLIST_PRODUCT_PATH    = 'Mageplaza\Productslider\Block\WishlistProducts';
    const CATEGORYID_PATH          = 'Mageplaza\Productslider\Block\CategoryId';
    const CUSTOM_PRODUCT_PATH      = 'Mageplaza\Productslider\Block\CustomProducts';

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Mageplaza\Productslider\Helper\Data
     */
    protected $helperData;

    /**
     * @var SliderFactory
     */
    protected $_sliderFactory;

    /**
     * @var \Mageplaza\Productslider\Model\ResourceModel\SliderFactory
     */
    protected $_resourceModelSliderFactory;

    /**
     * AddBlock constructor.
     * @param \Mageplaza\Productslider\Model\ResourceModel\SliderFactory $resourceModelSliderFactory
     * @param \Mageplaza\Productslider\Model\SliderFactory $sliderFactory
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Mageplaza\Productslider\Helper\Data $helperData
     */
    public function __construct(
        ResourceModelFactory $resourceModelSliderFactory,
        SliderFactory $sliderFactory,
        Http $request,
        Data $helperData
    )
    {
        $this->_resourceModelSliderFactory = $resourceModelSliderFactory;
        $this->_sliderFactory              = $sliderFactory;
        $this->request                     = $request;
        $this->helperData                  = $helperData;
    }

    /**
     * @param Observer $observer
     * @return $this|bool|void
     */
    public function execute(Observer $observer)
    {
        if (!$this->helperData->isEnabled()) {
            return;
        }

        $elementName    = $observer->getElementName();
        $output         = $observer->getTransport()->getOutput();
        $fullActionName = $this->request->getFullActionName();

        $event = $observer->getEvent();
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $event->getLayout();

        $types = [
            'content' => 'content',
            'sidebar' => 'catalog.leftnav'
        ];
        $type  = array_search($elementName, $types);

        if ($type !== false) {
            foreach ($this->helperData->getActiveSliders() as $slider) {
                $data = $this->getSliderLocation($slider);
                if ($fullActionName == $data['page_type'] || $data['page_type'] == 'allpage') {
                    $block = $this->getSliderProductType($slider);
                    $html  = $layout->createBlock($block)
                        ->setTemplate('Mageplaza_Productslider::productslider.phtml')
                        ->setSlider($slider);

                    $content = $html->toHtml();

                    if ($type == 'content') {
                        if ($data['location'] == 'content-top') {
                            $output = "<div id=\"mageplaza-productslider-block-before-{$type}-{$slider->getId()}\">$content</div>" . $output;
                        } else if ($data['location'] == 'content-bottom') {
                            $output = $output . "<div id=\"mageplaza-productslider-block-after-{$type}-{$slider->getId()}\">$content</div>";
                        }
                    }

                    if ($type == 'sidebar') {
                        if ($data['location'] == 'sidebar-top') {
                            $output = "<div id=\"mageplaza-productslider-block-before-{$type}-{$slider->getId()}\">$content</div>" . $output;
                        } else if ($data['location'] == 'sidebar-bottom') {
                            $output = $output . "<div id=\"mageplaza-productslider-block-after-{$type}-{$slider->getId()}\">$content</div>";
                        }
                    }
                }
            }
            $observer->getTransport()->setOutput($output);
        }

        return $this;
    }

    /**
     * Get Slider Type and Location
     *
     * @param $slider
     * @return mixed
     */
    public function getSliderLocation($slider)
    {
        $location          = explode('.', $slider->getLocation());
        $data['page_type'] = $location[0];
        $data['location']  = $location[1];

        return $data;
    }

    /**
     * Get Block Path of Product Type
     *
     * @param $slider
     * @return string
     */
    public function getSliderProductType($slider)
    {
        $block       = '';
        $productType = $slider->getProductType();

        switch ($productType) {
            case ProductType::NEW_PRODUCTS :
                $block = self::NEW_PRODUCT_PATH;
                break;
            case ProductType::BEST_SELLER_PRODUCTS :
                $block = self::BEST_SELLER_PATH;
                break;
            case ProductType::FEATURED_PRODUCTS :
                $block = self::FEATURED_PRODUCTS_PATH;
                break;
            case ProductType::MOSTVIEWED_PRODUCTS :
                $block = self::MOSTVIEWED_PRODUCTS_PATH;
                break;
            case ProductType::ONSALE_PRODUCTS :
                $block = self::ONSALE_PRODUCTS_PATH;
                break;
            case ProductType::RECENT_PRODUCT :
                $block = self::RECENT_PRODUCT_PATH;
                break;
            case ProductType::WISHLIST_PRODUCT :
                $block = self::WISHLIST_PRODUCT_PATH;
                break;
            case ProductType::CATEGORY :
                $block = self::CATEGORYID_PATH;
                break;
            case ProductType::CUSTOM_PRODUCTS :
                $block = self::CUSTOM_PRODUCT_PATH;
                break;
        }

        return $block;
    }
}
