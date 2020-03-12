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

namespace Mageplaza\Productslider\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Mageplaza\Productslider\Helper\Data;

/**
 * Class Slider
 * @package Mageplaza\Productslider\Model\ResourceModel
 */
class Slider extends AbstractDb
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Slider constructor.
     *
     * @param Context $context
     * @param Data $helper
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        Data $helper,
        $connectionName = null
    ) {
        $this->helper = $helper;

        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageplaza_productslider_slider', 'slider_id');
    }

    /**
     * @inheritdoc
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $storeIds = $object->getStoreIds();
        if (is_array($storeIds)) {
            $object->setStoreIds(implode(',', $storeIds));
        }

        $groupIds = $object->getCustomerGroupIds();
        if (is_array($groupIds)) {
            $object->setCustomerGroupIds(implode(',', $groupIds));
        }

        $displayAddition = $object->getDisplayAdditional();
        if (is_array($displayAddition)) {
            $object->setDisplayAdditional(implode(',', $displayAddition));
        }

        $responsiveItems = $object->getResponsiveItems();
        if ($responsiveItems && is_array($responsiveItems)) {
            $object->setResponsiveItems($this->helper->serialize($responsiveItems));
        } else {
            $object->setResponsiveItems($this->helper->serialize([]));
        }

        return parent::_beforeSave($object);
    }

    /**
     * @inheritdoc
     */
    protected function _afterLoad(AbstractModel $object)
    {
        parent::_afterLoad($object);

        if (!is_null($object->getResponsiveItems())) {
            $object->setResponsiveItems($this->helper->unserialize($object->getResponsiveItems()));
        } else {
            $object->setResponsiveItems(null);
        }

        return $this;
    }
}
