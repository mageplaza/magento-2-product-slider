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
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Mageplaza\Productslider\Controller\Adminhtml\Slider;
use Mageplaza\Productslider\Model\SliderFactory;

/**
 * Class Save
 * @package Mageplaza\Productslider\Controller\Adminhtml\Slider
 */
class Save extends Slider
{
    /**
     * Date filter
     *
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $_dateFilter;

    /**
     * Save constructor.
     * @param Context $context
     * @param SliderFactory $sliderFactory
     * @param Registry $coreRegistry
     * @param Date $dateFilter
     */
    public function __construct(
        Context $context,
        SliderFactory $sliderFactory,
        Registry $coreRegistry,
        Date $dateFilter
    )
    {
        $this->_dateFilter = $dateFilter;

        parent::__construct($context, $sliderFactory, $coreRegistry);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPost('slider')) {
            $data   = $this->_filterData($data);
            $slider = $this->_initSlider();

            try {
                $slider->addData($data)
                    ->save();
                $this->messageManager->addSuccessMessage(__('The Slider has been saved.'));
                $this->_session->setMageplazaProductsliderSliderData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath('*/*/edit', ['id' => $slider->getId(), '_current' => true]);

                    return $resultRedirect;
                }
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Slider. %1', $e->getMessage()));
                $this->_getSession()->setMageplazaProductsliderSliderData($data);
                $resultRedirect->setPath('*/*/edit', [
                    'id'       => $slider->getId(),
                    '_current' => true
                ]);

                return $resultRedirect;
            }
        }

        $resultRedirect->setPath('*/*/');

        return $resultRedirect;
    }

    /**
     * filter values
     *
     * @param array $data
     * @return array
     */
    protected function _filterData($data)
    {
        $inputFilter = new \Zend_Filter_Input(['from_date' => $this->_dateFilter,], [], $data);
        $data        = $inputFilter->getUnescaped();

        if (isset($data['responsive_items'])) {
            unset($data['responsive_items']['__empty']);
        }

        if ($products = $this->getRequest()->getParam('products')) {
            $data['product_ids'] = $products;
        }

        return $data;
    }
}
