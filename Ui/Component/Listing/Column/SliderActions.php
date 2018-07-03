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

namespace Mageplaza\Productslider\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class SliderActions extends Column
{
	/**
	 * Url path  to edit
	 *
	 * @var string
	 */
	const URL_PATH_EDIT = 'mpproductslider/slider/edit';

	/**
	 * Url path  to delete
	 *
	 * @var string
	 */
	const URL_PATH_DELETE = 'mpproductslider/slider/delete';

	/**
	 * URL builder
	 *
	 * @var \Magento\Framework\UrlInterface
	 */
	protected $_urlBuilder;

	/**
	 * SliderActions constructor.
	 * @param \Magento\Framework\UrlInterface $urlBuilder
	 * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
	 * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
	 * @param array $components
	 * @param array $data
	 */
	public function __construct(
		UrlInterface $urlBuilder,
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		array $components = [],
		array $data = []
	)
	{
		parent::__construct($context, $uiComponentFactory, $components, $data);

		$this->_urlBuilder = $urlBuilder;
	}

	/**
	 * Prepare Data Source
	 *
	 * @param array $dataSource
	 * @return array
	 */
	public function prepareDataSource(array $dataSource)
	{
		if (isset($dataSource['data']['items'])) {
			foreach ($dataSource['data']['items'] as & $item) {
				if (isset($item['slider_id'])) {
					$item[$this->getData('name')] = [
						'edit' => [
							'href'  => $this->_urlBuilder->getUrl(
								static::URL_PATH_EDIT,
								[
									'slider_id' => $item['slider_id']
								]
							),
							'label' => __('Edit')
						]
					];
				}
			}
		}

		return $dataSource;
	}
}
