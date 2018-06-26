<?php
/**
 * Created by PhpStorm.
 * User: nk
 * Date: 18/06/2018
 * Time: 09:22
 */

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Type;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Registry;

/**
 * Class Product
 * @package Mageplaza\Blog\Block\Adminhtml\Post\Edit\Tab
 */
class Products extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{

    public function render(AbstractElement $element)
    {
        return $this->getLayout()->createBlock('Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Type\Renderer\Products')->toHtml();
    }

}