<?php
/**
 * Created by PhpStorm.
 * User: nk
 * Date: 18/06/2018
 * Time: 15:16
 */

namespace Mageplaza\Productslider\Controller\Adminhtml\Slider;

use Magento\Framework\View\Result\LayoutFactory;
use Mageplaza\Productslider\Controller\Adminhtml\Slider;

/**
 * Class Products
 * @package Mageplaza\Blog\Controller\Adminhtml\Post
 */
class Products extends Slider
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    public function __construct(
        \Mageplaza\Productslider\Model\SliderFactory $sliderFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\App\Action\Context $context,
        LayoutFactory $resultLayoutFactory
    )
    {
        parent::__construct($sliderFactory, $coreRegistry, $context);

        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_initSlider(true);

        return $this->resultLayoutFactory->create();
    }
}
