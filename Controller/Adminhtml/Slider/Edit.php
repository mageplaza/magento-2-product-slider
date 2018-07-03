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

use Mageplaza\Productslider\Controller\Adminhtml\Slider;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Mageplaza\Productslider\Model\SliderFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;

class Edit extends Slider
{
	/**
	 * Backend session
	 *
	 * @var \Magento\Backend\Model\Session
	 */
	protected $_backendSession;

	/**
	 * Page factory
	 *
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $_resultPageFactory;

	/**
	 * Result JSON factory
	 *
	 * @var \Magento\Framework\Controller\Result\JsonFactory
	 */
	protected $_resultJsonFactory;

	/**
	 * Edit constructor.
	 * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 * @param \Mageplaza\Productslider\Model\SliderFactory $sliderFactory
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Backend\App\Action\Context $context
	 */
	public function __construct(
		PageFactory $resultPageFactory,
		JsonFactory $resultJsonFactory,
		SliderFactory $sliderFactory,
		Registry $registry,
		Context $context
	)
	{
		parent::__construct($sliderFactory, $registry, $context);

		$this->_backendSession    = $context->getSession();
		$this->_resultPageFactory = $resultPageFactory;
		$this->_resultJsonFactory = $resultJsonFactory;
	}

	/**
	 * is action allowed
	 *
	 * @return bool
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Mageplaza_Productslider::slider');
	}

	/**
	 * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
	 */
	public function execute()
	{
		$id     = $this->getRequest()->getParam('slider_id');
		$slider = $this->_initSlider();

		$resultPage = $this->_resultPageFactory->create();
		$resultPage->setActiveMenu('Mageplaza_Productslider::slider');
		$resultPage->getConfig()->getTitle()->set(__('Sliders'));
		if ($id) {
			$slider->load($id);
			if (!$slider->getId()) {
				$this->messageManager->addError(__('This Slider no longer exists.'));
				$resultRedirect = $this->_resultRedirectFactory->create();
				$resultRedirect->setPath(
					'mpproductslider/*/edit',
					[
						'slider_id' => $slider->getId(),
						'_current'  => true
					]
				);

				return $resultRedirect;
			}
		}

		$title = $slider->getId() ? $slider->getName() : __('New Slider');
		$resultPage->getConfig()->getTitle()->prepend($title);
		$data = $this->_backendSession->getData('mageplaza_productslider_slider_data', true);

		if (!empty($data)) {
			$slider->setData($data);
		}

		return $resultPage;
	}
}
