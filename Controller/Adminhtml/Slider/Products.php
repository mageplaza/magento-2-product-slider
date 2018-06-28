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
use Magento\Framework\Controller\Result\JsonFactory;

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

	protected $resultJsonFactory;

	public function __construct(
		\Mageplaza\Productslider\Model\SliderFactory $sliderFactory,
		\Magento\Framework\Registry $coreRegistry,
		\Magento\Backend\App\Action\Context $context,
		JsonFactory $resultJsonFactory,
		LayoutFactory $resultLayoutFactory
	)
	{
		parent::__construct($sliderFactory, $coreRegistry, $context);

		$this->resultLayoutFactory = $resultLayoutFactory;
		$this->resultJsonFactory = $resultJsonFactory;
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
		$result = $this->resultJsonFactory->create();
		$resultLayout = $this->resultLayoutFactory->create();
		$block = $resultLayout->getLayout()->getBlock('slider.edit.tab.product')->toHtml();
		$block .= $resultLayout->getLayout()->getBlock('product_grid_serializer')->toHtml();
		$result->setData(['output' => $block]);

		return $result;
	}
}
