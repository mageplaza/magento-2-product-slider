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

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\LayoutFactory;

class ProductsGrid extends Action
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
		LayoutFactory $resultLayoutFactory
	)
	{
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