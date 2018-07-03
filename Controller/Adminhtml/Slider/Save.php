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
use Magento\Backend\Helper\Js;
use Mageplaza\Productslider\Helper\Data;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Mageplaza\Productslider\Model\SliderFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;

class Save extends Slider
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
	 * @var \Mageplaza\Productslider\Helper\Data
	 */
	protected $_helperData;

	/**
	 * @var \Magento\Backend\Helper\Js
	 */
	protected $_jsHelper;

	/**
	 * Save constructor.
	 * @param \Magento\Backend\Helper\Js $jsHelper
	 * @param \Mageplaza\Productslider\Helper\Data $helper
	 * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
	 * @param \Mageplaza\Productslider\Model\SliderFactory $sliderFactory
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Backend\App\Action\Context $context
	 */
	public function __construct(
		Js $jsHelper,
		Data $helper,
		Date $dateFilter,
		SliderFactory $sliderFactory,
		Registry $registry,
		Context $context
	)
	{
		parent::__construct($sliderFactory, $registry, $context);

		$this->_jsHelper       = $jsHelper;
		$this->_helperData     = $helper;
		$this->_backendSession = $context->getSession();
		$this->_dateFilter     = $dateFilter;
	}

	/**
	 * @return \Magento\Framework\Controller\Result\Redirect
	 */
	public function execute()
	{
		$data           = $this->getRequest()->getPost('slider');
		$resultRedirect = $this->resultRedirectFactory->create();

		if ($data) {
			if (isset($data['display_additional'])) {
				$data['display_additional'] = $this->_helperData->serialize($data['display_additional']);
			}
			if (isset($data['responsive_items'])) {
				unset($data['responsive_items'][count($data['responsive_items']) - 1]);
				$data['responsive_items'] = $this->_helperData->serialize($this->formatResponsiveItems($data['responsive_items']));
			}
			$paramIds            = $this->getRequest()->getParam('products');
			$data['product_ids'] = $this->_helperData->serialize(explode('&', $paramIds));

			$data   = $this->_filterData($data);
			$slider = $this->_initSlider();
			$slider->setData($data);

			try {
				$slider->save();
				$this->messageManager->addSuccess(__('The Slider has been saved.'));
				$this->_backendSession->setMageplazaProductsliderSliderData(false);
				if ($this->getRequest()->getParam('back')) {
					$resultRedirect->setPath(
						'mpproductslider/*/edit',
						[
							'slider_id' => $slider->getId(),
							'_current'  => true
						]
					);

					return $resultRedirect;
				}
				$resultRedirect->setPath('mpproductslider/*/');

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
				'mpproductslider/*/edit',
				[
					'slider_id' => $slider->getId(),
					'_current'  => true
				]
			);

			return $resultRedirect;
		}
		$resultRedirect->setPath('mpproductslider/*/');

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
		if (isset($data['store_views'])) {
			if (is_array($data['store_views'])) {
				$data['store_views'] = implode(',', $data['store_views']);
			}
		}

		return $data;
	}

	/**
	 * Format Responsive config
	 *
	 * @param $data
	 * @return array
	 */
	public function formatResponsiveItems($data)
	{
		$resData = [];

		foreach ($data as $items) {
			foreach ($items as $id => $item) {
				foreach ($item as $col => $value) {
					$resData[$id][$col] = $value;
				}
			}
		}

		return $resData;
	}

}
