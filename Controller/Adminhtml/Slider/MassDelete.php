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
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\Productslider\Model\ResourceModel\Slider\CollectionFactory;
use Magento\Backend\App\Action\Context;

class MassDelete extends Action
{
	/**
	 * Mass Action Filter
	 *
	 * @var \Magento\Ui\Component\MassAction\Filter
	 */
	protected $_filter;

	/**
	 * Collection Factory
	 *
	 * @var \Mageplaza\Productslider\Model\ResourceModel\Slider\CollectionFactory
	 */
	protected $_collectionFactory;

	/**
	 * MassDelete constructor.
	 * @param \Magento\Ui\Component\MassAction\Filter $filter
	 * @param \Mageplaza\Productslider\Model\ResourceModel\Slider\CollectionFactory $collectionFactory
	 * @param \Magento\Backend\App\Action\Context $context
	 */
	public function __construct(
		Filter $filter,
		CollectionFactory $collectionFactory,
		Context $context
	)
	{
		parent::__construct($context);

		$this->_filter            = $filter;
		$this->_collectionFactory = $collectionFactory;
	}


	/**
	 * execute action
	 *
	 * @return \Magento\Backend\Model\View\Result\Redirect
	 */
	public function execute()
	{
		$collection = $this->_filter->getCollection($this->_collectionFactory->create());

		$delete = 0;
		foreach ($collection as $item) {
			/** @var \Mageplaza\Productslider\Model\Slider $item */
			$item->delete();
			$delete++;
		}
		$this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $delete));
		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);

		return $resultRedirect->setPath('*/*/');
	}
}
