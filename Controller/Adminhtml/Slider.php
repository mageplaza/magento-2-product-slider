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

namespace Mageplaza\Productslider\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Mageplaza\Productslider\Model\SliderFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;

abstract class Slider extends Action
{
	/**
	 * Slider Factory
	 *
	 * @var \Mageplaza\Productslider\Model\SliderFactory
	 */
	protected $_sliderFactory;

	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry;

	/**
	 * Result redirect factory
	 *
	 * @var \Magento\Backend\Model\View\Result\RedirectFactory
	 */
	protected $_resultRedirectFactory;

	/**
	 * Slider constructor.
	 * @param \Mageplaza\Productslider\Model\SliderFactory $sliderFactory
	 * @param \Magento\Framework\Registry $coreRegistry
	 * @param \Magento\Backend\App\Action\Context $context
	 */
	public function __construct(
		SliderFactory $sliderFactory,
		Registry $coreRegistry,
		Context $context
	)
	{
		parent::__construct($context);

		$this->_sliderFactory         = $sliderFactory;
		$this->_coreRegistry          = $coreRegistry;
		$this->_resultRedirectFactory = $context->getResultRedirectFactory();
	}

	/**
	 * Init Slider
	 *
	 * @return \Mageplaza\Productslider\Model\Slider
	 */
	protected function _initSlider()
	{
		$sliderId = (int)$this->getRequest()->getParam('slider_id');
		$slider   = $this->_sliderFactory->create();

		if ($sliderId) {
			$slider->load($sliderId);
		}
		$this->_coreRegistry->register('mageplaza_productslider_slider', $slider);

		return $slider;
	}
}
