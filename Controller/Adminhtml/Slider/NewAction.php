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
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\App\Action\Context;

class NewAction extends Action
{
	/**
	 * Redirect result factory
	 *
	 * @var \Magento\Backend\Model\View\Result\ForwardFactory
	 */
	protected $_resultForwardFactory;

	/**
	 * NewAction constructor.
	 * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
	 * @param \Magento\Backend\App\Action\Context $context
	 */
	public function __construct(
		ForwardFactory $resultForwardFactory,
		Context $context
	)
	{
		parent::__construct($context);

		$this->_resultForwardFactory = $resultForwardFactory;
	}

	/**
	 * forward to edit
	 *
	 * @return \Magento\Backend\Model\View\Result\Forward
	 */
	public function execute()
	{
		$resultForward = $this->_resultForwardFactory->create();
		$resultForward->forward('edit');

		return $resultForward;
	}
}
