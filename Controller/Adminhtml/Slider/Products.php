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

namespace Mageplaza\Productslider\Controller\Adminhtml\Slider;

use Magento\Framework\View\Result\LayoutFactory;
use Mageplaza\Productslider\Controller\Adminhtml\Slider;
use Magento\Framework\Controller\Result\JsonFactory;
use Mageplaza\Productslider\Model\SliderFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;

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

	/**
	 * @var \Magento\Framework\Controller\Result\JsonFactory
	 */
	protected $resultJsonFactory;

	public function __construct(
		SliderFactory $sliderFactory,
		Registry $coreRegistry,
		Context $context,
		JsonFactory $resultJsonFactory,
		LayoutFactory $resultLayoutFactory
	)
	{
		parent::__construct($sliderFactory, $coreRegistry, $context);

		$this->resultLayoutFactory = $resultLayoutFactory;
		$this->resultJsonFactory   = $resultJsonFactory;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function _isAllowed()
	{
		return true;
	}

	/**
	 * Save action
	 *
	 * @return \Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{
		$result       = $this->resultJsonFactory->create();
		$resultLayout = $this->resultLayoutFactory->create();
		$block        = $resultLayout->getLayout()->getBlock('slider.edit.tab.product')->toHtml();
		$block        .= $resultLayout->getLayout()->getBlock('product_grid_serializer')->toHtml();
		$result->setData(['output' => $block]);

		return $result;
	}
}
