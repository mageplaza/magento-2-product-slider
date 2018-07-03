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

class Delete extends Slider
{
	/**
	 * execute action
	 *
	 * @return \Magento\Backend\Model\View\Result\Redirect
	 */
	public function execute()
	{
		$resultRedirect = $this->_resultRedirectFactory->create();
		$id             = $this->getRequest()->getParam('slider_id');
		if ($id) {
			$name = "";
			try {
				/** @var \Mageplaza\Productslider\Model\Slider $slider */
				$slider = $this->_sliderFactory->create();
				$slider->load($id);
				$name = $slider->getName();
				$slider->delete();
				$this->messageManager->addSuccess(__('The Slider has been deleted.'));
				$this->_eventManager->dispatch(
					'adminhtml_mageplaza_productslider_slider_on_delete',
					['name' => $name, 'status' => 'success']
				);
				$resultRedirect->setPath('mpproductslider/*/');

				return $resultRedirect;
			} catch (\Exception $e) {
				$this->_eventManager->dispatch(
					'adminhtml_mageplaza_productslider_slider_on_delete',
					['name' => $name, 'status' => 'fail']
				);
				// display error message
				$this->messageManager->addError($e->getMessage());
				// go back to edit form
				$resultRedirect->setPath('mpproductslider/*/edit', ['slider_id' => $id]);

				return $resultRedirect;
			}
		}
		// display error message
		$this->messageManager->addError(__('Slider to delete was not found.'));
		// go to grid
		$resultRedirect->setPath('mpproductslider/*/');

		return $resultRedirect;
	}
}
