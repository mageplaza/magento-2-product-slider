<?php
/**
 * Mageplaza_Productslider extension
 *                     NOTICE OF LICENSE
 * 
 *                     This source file is subject to the MIT License
 *                     that is bundled with this package in the file LICENSE.txt.
 *                     It is also available through the world-wide-web at this URL:
 *                     https://www.mageplaza.com/LICENSE.txt
 * 
 *                     @category  Mageplaza
 *                     @package   Mageplaza_Productslider
 *                     @copyright Copyright (c) 2016
 *                     @license   https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\Productslider\Controller\Adminhtml;

abstract class Slider extends \Magento\Backend\App\Action
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
     * constructor
     * 
     * @param \Mageplaza\Productslider\Model\SliderFactory $sliderFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Mageplaza\Productslider\Model\SliderFactory $sliderFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_sliderFactory         = $sliderFactory;
        $this->_coreRegistry          = $coreRegistry;
        $this->_resultRedirectFactory = $context->getResultRedirectFactory();
        parent::__construct($context);
    }

    /**
     * Init Slider
     *
     * @return \Mageplaza\Productslider\Model\Slider
     */
    protected function _initSlider()
    {
        $sliderId  = (int) $this->getRequest()->getParam('slider_id');
        /** @var \Mageplaza\Productslider\Model\Slider $slider */
        $slider    = $this->_sliderFactory->create();
        if ($sliderId) {
            $slider->load($sliderId);
        }
        $this->_coreRegistry->register('mageplaza_productslider_slider', $slider);
        return $slider;
    }
}
