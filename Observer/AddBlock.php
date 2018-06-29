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

namespace Mageplaza\Productslider\Observer;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageplaza\Productslider\Helper\Data;
use Mageplaza\Productslider\Model\Config\Source\ProductType;
use Mageplaza\Productslider\Model\SliderFactory;

/**
 * Class AddBlock
 * @package Mageplaza\AutoRelated\Observer
 */
class AddBlock implements ObserverInterface
{
	const NEW_PRODUCT_PATH = 'Mageplaza\Productslider\Block\NewProducts';
	const BEST_SELLER_PATH = 'Mageplaza\Productslider\Block\BestSellerProducts';
	const FEATURED_PRODUCTS_PATH = 'Mageplaza\Productslider\Block\FeaturedProducts';
	const MOSTVIEWED_PRODUCTS_PATH = 'Mageplaza\Productslider\Block\MostViewedProducts';
	const ONSALE_PRODUCTS_PATH = 'Mageplaza\Productslider\Block\OnSaleProduct';
	const RECENT_PRODUCT_PATH = 'Mageplaza\Productslider\Block\RecentProducts';
	const WISHLIST_PRODUCT_PATH = 'Mageplaza\Productslider\Block\WishlistProducts';
	const CATEGORYID_PATH = 'Mageplaza\Productslider\Block\CategoryId';
	const CUSTOM_PRODUC_PATH = 'Mageplaza\Productslider\Block\CustomProducts';

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
	 * AddBlock constructor.
	 * @param SliderFactory $sliderFactory
	 * @param Http $request
	 * @param Data $helperData
	 */
	public function __construct(
		SliderFactory $sliderFactory,
		Http $request,
		Data $helperData
	)
	{
		$this->_sliderFactory = $sliderFactory;
		$this->request        = $request;
		$this->helperData     = $helperData;
	}

	/**
	 * @param Observer $observer
	 * @return $this|bool|void
	 */
	public function execute(Observer $observer)
	{
		if (!$this->helperData->isEnabled()) {
			return false;
		}

		$sliders        = $this->_sliderFactory->create()->getCollection();
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

		$type = array_search($elementName, $types);

		if ($type !== false) {
			foreach ($sliders as $slider) {
				$data = $this->getSliderLocation($slider);
				if ($slider->getStatus()) {
					if ($fullActionName == $data['type'] || $data['type'] == 'allpage') {
						$id = $slider->getSliderId();
						$js = $layout->createBlock($this->getSliderProductType($slider))
							->setTemplate('Mageplaza_Productslider::productslider.phtml')
							->setSlider($slider);

						$content = $js->toHtml();

						if ($type == 'content') {
							if ($data['location'] == 'content-top') {
								$output = "<div id=\"mageplaza-productslider-block-before-{$type}-{$id}\">$content</div>" . $output;
							} else if ($data['location'] == 'content-bottom') {
								$output = $output . "<div id=\"mageplaza-productslider-block-after-{$type}-{$id}\">$content</div>";
							}
						}

						if ($type == 'sidebar') {
							if ($data['location'] == 'sidebar-top') {
								$output = "<div id=\"mageplaza-productslider-block-before-{$type}-{$id}\">$content</div>" . $output;
							} else if ($data['location'] == 'sidebar-bottom') {
								$output = $output . "<div id=\"mageplaza-productslider-block-after-{$type}-{$id}\">$content</div>";
							}
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
		$location         = explode('.', $slider->getLocation());
		$data['type']     = $location[0];
		$data['location'] = $location[1];

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
				$block = self::BEST_SELLER_PATH;
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
			case ProductType::CATEGORYID :
				$block = self::CATEGORYID_PATH;
				break;
			case ProductType::CUSTOM_PRODUCTS :
				$block = self::CUSTOM_PRODUC_PATH;
				break;
		}

		return $block;
	}

}
