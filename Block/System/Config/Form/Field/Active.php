<?php
/**
 * Created by PhpStorm.
 * User: nk
 * Date: 12/06/2018
 * Time: 14:48
 */

namespace Mageplaza\Productslider\Block\System\Config\Form\Field;

class Active extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    protected function _prepareToRender() {
        $this->addColumn('col_1', ['label' => __('Screen size max'),  'renderer' => false]);
        $this->addColumn('col_2', ['label' => __('Number of items'),  'renderer' => false]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
//        $this->_addAfter = false;
//        $this->_addButtonLabel = __('Add');
    }
}