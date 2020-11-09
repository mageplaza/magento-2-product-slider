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

namespace Mageplaza\Productslider\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Mageplaza\Productslider\Model\SliderFactory;

/**
 * Class Slider
 * @package Mageplaza\Productslider\Controller\Adminhtml
 */
abstract class Slider extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Mageplaza_Productslider::slider';

    /**
     * Slider Factory
     *
     * @var SliderFactory
     */
    protected $_sliderFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Slider constructor.
     *
     * @param SliderFactory $sliderFactory
     * @param Registry $coreRegistry
     * @param Context $context
     */
    public function __construct(
        Context $context,
        SliderFactory $sliderFactory,
        Registry $coreRegistry
    ) {
        $this->_sliderFactory = $sliderFactory;
        $this->_coreRegistry = $coreRegistry;

        parent::__construct($context);
    }

    /**
     * Init Slider
     *
     * @return \Mageplaza\Productslider\Model\Slider
     */
    protected function _initSlider()
    {
        $slider = $this->_sliderFactory->create();

        $sliderId = (int)$this->getRequest()->getParam('id');
        if ($sliderId) {
            $slider->load($sliderId);
        }
        $this->_coreRegistry->register('mageplaza_productslider_slider', $slider);

        return $slider;
    }
}
