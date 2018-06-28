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
	const TBL_ATT_PRODUCT = 'mageplaza_productslider_product_list';

	protected $_dateTime;

	protected $_date;

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

	protected function _construct()
	{
		$this->_init('mageplaza_productslider_slider', 'slider_id');
	}

	public function getCustomerGroupByRuleId($ruleId)
	{
		$tableName = $this->getTable('mageplaza_productslider_slider_customer_group');
		$select    = $this->getConnection()->select()
			->from($tableName, 'customer_group_id')
			->where('slider_id = ?', $ruleId);

		return $this->getConnection()->fetchCol($select);
	}

	/**
	 * store view rule config
	 *
	 * @param string $ruleId
	 * @return array
	 */
	public function getStoresByRuleId($sliderId)
	{
		$tableName = $this->getTable('mageplaza_productslider_slider_store');
		$select    = $this->getConnection()->select()
			->from($tableName, 'store_id')
			->where('slider_id = ?', $sliderId);

		return $this->getConnection()->fetchCol($select);
	}


	public function updateStore($data = [], $sliderId)
	{
		$dataInsert = [];
		foreach ($data as $storeId) {
			$dataInsert[] = [
				'slider_id'  => $sliderId,
				'store_id' => $storeId
			];
		}
		$this->updateMultipleData('mageplaza_productslider_slider_store', $dataInsert);
	}

	public function updateCustomerGroup($data = [], $sliderId)
	{
		$dataInsert = [];
		foreach ($data as $customerGroupId) {
			$dataInsert[] = [
				'slider_id'           => $sliderId,
				'customer_group_id' => $customerGroupId
			];
		}
		$this->updateMultipleData('mageplaza_productslider_slider_customer_group', $dataInsert);
	}

	public function deleteOldData($sliderId)
	{
		if ($sliderId) {
			$where = ['slider_id = ?' => $sliderId];
			$this->deleteMultipleData('mageplaza_productslider_slider_store', $where);
			$this->deleteMultipleData('mageplaza_productslider_slider_customer_group', $where);
		}
	}

	public function deleteMultipleData($tableName, $where = [])
	{
		$table = $this->getTable($tableName);
		if ($table && !empty($where)) {
			$this->getConnection()->delete($table, $where);
		}
	}

	public function updateMultipleData($tableName, $data = [])
	{
		$table = $this->getTable($tableName);
		if ($table && !empty($data)) {
			$this->getConnection()->insertMultiple($table, $data);
		}
	}











//    public function getSliderNameById($id)
//    {
//        $adapter = $this->getConnection();
//        $select = $adapter->select()
//            ->from($this->getMainTable(), 'name')
//            ->where('slider_id = :slider_id');
//        $binds = ['slider_id' => (int)$id];
//        return $adapter->fetchOne($select, $binds);
//    }
//    /**
//     * before save callback
//     *
//     * @param \Magento\Framework\Model\AbstractModel|\Mageplaza\Productslider\Model\Slider $object
//     * @return $this
//     */
//    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
//    {
//        $object->setUpdatedAt($this->_date->date());
//        if ($object->isObjectNew()) {
//            $object->setCreatedAt($this->_date->date());
//        }
//        foreach (['active_from'] as $field) {
//            $value = !$object->getData($field) ? null : $object->getData($field);
//            $object->setData($field, $this->_dateTime->formatDate($value));
//        }
//        return parent::_beforeSave($object);
//    }
}
