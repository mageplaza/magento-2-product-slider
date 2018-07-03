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

namespace Mageplaza\Productslider\Block\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Active extends AbstractFieldArray
{
    protected function _prepareToRender() {
        $this->addColumn('col_1', ['label' => __('Screen size max'),  'renderer' => false]);
        $this->addColumn('col_2', ['label' => __('Number of items'),  'renderer' => false]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

}