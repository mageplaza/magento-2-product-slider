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
use Mageplaza\Productslider\Model\SliderFactory;
use Mageplaza\Productslider\Model\ResourceModel\SliderFactory as ResourceModelFactory;

/**
 * Class AddBlock
 * @package Mageplaza\AutoRelated\Observer
 */
class AddBlock implements ObserverInterface
{
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
			return false;
		}

		$resourceModel  = $this->_resourceModelSliderFactory->create();
		$sliderIds      = $resourceModel->getSliderIds();
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
			foreach ($sliderIds as $sliderId) {
				$slider = $this->_sliderFactory->create()->load($sliderId);
				$data   = $resourceModel->getSliderLocation($slider);
				if ($fullActionName == $data['page_type'] || $data['page_type'] == 'allpage') {
					$block = $resourceModel->getSliderProductType($slider);
					$html  = $layout->createBlock($block)
						->setTemplate('Mageplaza_Productslider::productslider.phtml')
						->setSlider($slider);

					$content = $html->toHtml();

					if ($type == 'content') {
						if ($data['location'] == 'content-top') {
							$output = "<div id=\"mageplaza-productslider-block-before-{$type}-{$sliderId}\">$content</div>" . $output;
						} else if ($data['location'] == 'content-bottom') {
							$output = $output . "<div id=\"mageplaza-productslider-block-after-{$type}-{$sliderId}\">$content</div>";
						}
					}

					if ($type == 'sidebar') {
						if ($data['location'] == 'sidebar-top') {
							$output = "<div id=\"mageplaza-productslider-block-before-{$type}-{$sliderId}\">$content</div>" . $output;
						} else if ($data['location'] == 'sidebar-bottom') {
							$output = $output . "<div id=\"mageplaza-productslider-block-after-{$type}-{$sliderId}\">$content</div>";
						}
					}
				}
			}
			$observer->getTransport()->setOutput($output);
		}

		return $this;
	}

}
