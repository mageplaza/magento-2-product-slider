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

class Save extends \Mageplaza\Productslider\Controller\Adminhtml\Slider
{
    /**
     * Backend session
     * 
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * Date filter
     * 
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $_dateFilter;

    /**
     * constructor
     * 
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Mageplaza\Productslider\Model\SliderFactory $sliderFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Mageplaza\Productslider\Model\SliderFactory $sliderFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_backendSession = $context->getSession();
        $this->_dateFilter     = $dateFilter;
        parent::__construct($sliderFactory, $registry, $context);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('slider');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->_filterData($data);
            $slider = $this->_initSlider();
            $slider->setData($data);
            $this->_eventManager->dispatch(
                'mageplaza_productslider_slider_prepare_save',
                [
                    'slider' => $slider,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $slider->save();
                $this->messageManager->addSuccess(__('The Slider has been saved.'));
                $this->_backendSession->setMageplazaProductsliderSliderData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'mageplaza_productslider/*/edit',
                        [
                            'slider_id' => $slider->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('mageplaza_productslider/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Slider.'));
            }
            $this->_getSession()->setMageplazaProductsliderSliderData($data);
            $resultRedirect->setPath(
                'mageplaza_productslider/*/edit',
                [
                    'slider_id' => $slider->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('mageplaza_productslider/*/');
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
        $inputFilter = new \Zend_Filter_Input(
            [
                'active_from' => $this->_dateFilter,
            ],
            [],
            $data
        );
        $data = $inputFilter->getUnescaped();
        if (isset($data['store_views'])) {
            if (is_array($data['store_views'])) {
                $data['store_views'] = implode(',', $data['store_views']);
            }
        }
        return $data;
    }
}
