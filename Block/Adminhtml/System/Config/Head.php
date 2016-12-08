<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Mageplaza\Productslider\Block\Adminhtml\System\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Head extends \Magento\Config\Block\System\Config\Form\Field
{


	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		array $data = []
	) {
		parent::__construct($context, $data);
	}

	/**
	 * Set template
	 *
	 * @return void
	 */
//	protected function _construct()
//	{
//		parent::_construct();
//		$this->setTemplate('Mageplaza_Productslider::system/config/head.phtml');
//	}

	/**
	 * Render text
	 *
	 * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
	 * @return string
	 */
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
	{
		 $html='<ul class="mageplaza-head" style="position: static; margin-left: 10em;">
<li>
<a href="https://docs.mageplaza.com/product-slider-m2/" target="_blank">User Guide</a> <br>
</li>
<li>
<a href="http://magento.stackexchange.com/" target="_blank">Report a problem</a> <br>
</li>

</ul>';
		 return $html;

//		return parent::render($element);
	}

	/**
	 * Return element html
	 *
	 * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
	 * @return string
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
//	protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
//	{
//		return $this->_toHtml();
//	}
}
