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
 * @copyright   Copyright (c) 2018 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\Productslider\Model\ResourceModel\Slider\CollectionFactory;

/**
 * Class MassStatus
 * @package Mageplaza\Blog\Controller\Adminhtml\Post
 */
class MassStatus extends Action
{
	/**
	 * Mass Action Filter
	 *
	 * @var \Magento\Ui\Component\MassAction\Filter
	 */
	public $filter;

	/**
	 * Collection Factory
	 *
	 * @var \Mageplaza\Productslider\Model\ResourceModel\Slider\CollectionFactory
	 */
	public $collectionFactory;

	/**
	 * MassStatus constructor.
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Ui\Component\MassAction\Filter $filter
	 * @param \Mageplaza\Productslider\Model\ResourceModel\Slider\CollectionFactory $collectionFactory
	 */
	public function __construct(
		Context $context,
		Filter $filter,
		CollectionFactory $collectionFactory
	)
	{
		parent::__construct($context);

		$this->filter            = $filter;
		$this->collectionFactory = $collectionFactory;
	}

	/**
	 * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
	 * @throws LocalizedException
	 */
	public function execute()
	{
		$collection    = $this->filter->getCollection($this->collectionFactory->create());
		$status        = (int)$this->getRequest()->getParam('status');
		$sliderUpdated = 0;
		foreach ($collection as $slider) {
			try {
				$slider->setStatus($status)
					->save();

				$sliderUpdated++;
			} catch (LocalizedException $e) {
				$this->messageManager->addErrorMessage($e->getMessage());
			} catch (\Exception $e) {
				$this->_getSession()->addException($e, __('Something went wrong while updating status for %1.', $slider->getName()));
			}
		}

		if ($sliderUpdated) {
			$this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated.', $sliderUpdated));
		}

		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

		return $resultRedirect->setPath('*/*/');
	}
}
