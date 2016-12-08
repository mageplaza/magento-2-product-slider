<?php
/**
 * Mageplaza_Productslider extension
 *                     NOTICE OF LICENSE
 * 
 *                     This source file is subject to the MIT License
 *                     that is bundled with this package in the file LICENSE.txt.
 *                     It is also available through the world-wide-web at this URL:
 *                     https://www.mageplaza.com/LICENSE.txt
 * 
 *                     @category  Mageplaza
 *                     @package   Mageplaza_Productslider
 *                     @copyright Copyright (c) 2016
 *                     @license   https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\Productslider\Controller\Adminhtml\Slider;

class Delete extends \Mageplaza\Productslider\Controller\Adminhtml\Slider
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('slider_id');
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
                $resultRedirect->setPath('mageplaza_productslider/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_mageplaza_productslider_slider_on_delete',
                    ['name' => $name, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath('mageplaza_productslider/*/edit', ['slider_id' => $id]);
                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager->addError(__('Slider to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('mageplaza_productslider/*/');
        return $resultRedirect;
    }
}
