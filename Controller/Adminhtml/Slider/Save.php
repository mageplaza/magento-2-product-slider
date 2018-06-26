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

    protected $helper;

    /**
     * Save constructor.
     * @param \Mageplaza\Productslider\Helper\Data $helper
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Mageplaza\Productslider\Model\SliderFactory $sliderFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Mageplaza\Productslider\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Mageplaza\Productslider\Model\SliderFactory $sliderFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->helper = $helper;
        $this->_backendSession = $context->getSession();
        $this->_dateFilter     = $dateFilter;
        parent::__construct($sliderFactory, $registry, $context);
    }


    public function execute()
    {
        $data = $this->getRequest()->getPost('slider');
//        \Zend_Debug::dump($this->getRequest()->getParams());die;
//        \Zend_Debug::dump($data);die;

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['display_additional'])) {
                $data['display_additional'] = $this->helper->serialize($data['display_additional']);
            }
            if (isset($data['responsive_items'])) {
                unset($data['responsive_items'][count($data['responsive_items']) -1]);
                $data['responsive_items'] =  $this->helper->serialize($this->formatResponsiveItems($data['responsive_items']));
            }

            $data = $this->_filterData($data);
            $slider = $this->_initSlider();
            $slider->setData($data);

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
        $inputFilter = new \Zend_Filter_Input(['from_date' => $this->_dateFilter,], [], $data);
        $data = $inputFilter->getUnescaped();
        if (isset($data['store_views'])) {
            if (is_array($data['store_views'])) {
                $data['store_views'] = implode(',', $data['store_views']);
            }
        }
        return $data;
    }

    public function formatResponsiveItems($data){
        $resData = [];

        foreach ($data as $items){
            foreach ($items as $id => $item){
                foreach ($item as $col => $value){
                    $resData[$id][$col]= $value;
                }
            }
        }

        return $resData;
    }

}
