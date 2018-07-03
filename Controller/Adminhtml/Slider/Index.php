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
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;

class Index extends Action
{
	/**
	 * Page result factory
	 *
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $_resultPageFactory;

	/**
	 * Page factory
	 *
	 * @var \Magento\Backend\Model\View\Result\Page
	 */
	protected $_resultPage;

	/**
	 * Index constructor.
	 * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 * @param \Magento\Backend\App\Action\Context $context
	 */
	public function __construct(
		PageFactory $resultPageFactory,
		Context $context
	)
	{
		parent::__construct($context);

		$this->_resultPageFactory = $resultPageFactory;
	}

	/**
	 * execute the action
	 *
	 * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
	 */
	public function execute()
	{
		$this->_setPageData();

		return $this->getResultPage();
	}

	/**
	 * instantiate result page object
	 *
	 * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
	 */
	public function getResultPage()
	{
		if (is_null($this->_resultPage)) {
			$this->_resultPage = $this->_resultPageFactory->create();
		}

		return $this->_resultPage;
	}

	/**
	 * set page data
	 *
	 * @return $this
	 */
	protected function _setPageData()
	{
		$resultPage = $this->getResultPage();
		$resultPage->getConfig()->getTitle()->prepend((__('Sliders')));

		return $this;
	}
}
