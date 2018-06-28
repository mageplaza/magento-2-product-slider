<?php
/**
 * Created by PhpStorm.
 * User: nk
 * Date: 18/06/2018
 * Time: 15:15
 */

namespace Mageplaza\Productslider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;

class ProductsGrid extends \Magento\Backend\App\Action
{
	/**
	 * @var \Magento\Framework\View\Result\LayoutFactory
	 */
	protected $_resultLayoutFactory;

	/**
	 * ProductsGrid constructor.
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
	 */
	public function __construct(
		Action\Context $context,
		\Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
	) {
		parent::__construct($context);
		$this->_resultLayoutFactory = $resultLayoutFactory;
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
		$resultLayout = $this->_resultLayoutFactory->create();
		$resultLayout->getLayout()->getBlock('slider.edit.tab.product')
			->setInBanner($this->getRequest()->getPost('slider_products', null));

		return $resultLayout;
	}
}