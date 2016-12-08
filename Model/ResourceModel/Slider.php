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
namespace Mageplaza\Productslider\Model\ResourceModel;

class Slider extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Date time handler
     * 
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    /**
     * Date model
     * 
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * constructor
     * 
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        $this->_dateTime = $dateTime;
        $this->_date     = $date;
        parent::__construct($context);
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
     * Retrieves Slider Name from DB by passed id.
     *
     * @param string $id
     * @return string|bool
     */
    public function getSliderNameById($id)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'name')
            ->where('slider_id = :slider_id');
        $binds = ['slider_id' => (int)$id];
        return $adapter->fetchOne($select, $binds);
    }
    /**
     * before save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Mageplaza\Productslider\Model\Slider $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setUpdatedAt($this->_date->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->_date->date());
        }
        foreach (['active_from'] as $field) {
            $value = !$object->getData($field) ? null : $object->getData($field);
            $object->setData($field, $this->_dateTime->formatDate($value));
        }
        return parent::_beforeSave($object);
    }
}
