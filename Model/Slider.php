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

namespace Mageplaza\Productslider\Model;

/**
 * @method Slider setName($name)
 * @method Slider setStoreViews($storeViews)
 * @method Slider setActiveFrom($activeFrom)
 * @method Slider setActiveTo($activeTo)
 * @method Slider setStatus($status)
 * @method Slider setSerializedData($serializedData)
 * @method mixed getName()
 * @method mixed getStoreViews()
 * @method mixed getActiveFrom()
 * @method mixed getActiveTo()
 * @method mixed getStatus()
 * @method mixed getSerializedData()
 * @method Slider setCreatedAt(\string $createdAt)
 * @method string getCreatedAt()
 * @method Slider setUpdatedAt(\string $updatedAt)
 * @method string getUpdatedAt()
 */
class Slider extends \Magento\Framework\Model\AbstractModel
{
	/**
	 * Cache tag
	 *
	 * @var string
	 */
	const CACHE_TAG = 'mageplaza_productslider_slider';

	/**
	 * Cache tag
	 *
	 * @var string
	 */
	protected $_cacheTag = 'mageplaza_productslider_slider';

	/**
	 * Event prefix
	 *
	 * @var string
	 */
	protected $_eventPrefix = 'mageplaza_productslider_slider';


	/**
	 * Initialize resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Mageplaza\Productslider\Model\ResourceModel\Slider');
	}

	/**
	 * Get identities
	 *
	 * @return array
	 */
	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	/**
	 * @param \Mageplaza\Productslider\Model\Slider $object
	 * @return array
	 */
	public function getProducts(\Mageplaza\Productslider\Model\Slider $object)
	{
		$tbl    = $this->getResource()->getTable(\Mageplaza\Productslider\Model\ResourceModel\Slider::TBL_ATT_PRODUCT);
		$select = $this->getResource()->getConnection()->select()->from(
			$tbl,
			['product_id']
		)
			->where(
				'slider_id = ?',
				(int)$object->getSliderId()
			);

		return $this->getResource()->getConnection()->fetchCol($select);
	}

	/**
	 * @return $this
	 */
	public function afterSave()
	{
		if ($this->getCustomerGroupIds() || $this->getStoreIds()) {
			$this->getResource()->deleteOldData($this->getId());
			if ($storeIds = $this->getStoreIds()) {
				$this->getResource()->updateStore($storeIds, $this->getId());
			}
			if ($groupIds = $this->getCustomerGroupIds()) {
				$this->getResource()->updateCustomerGroup($groupIds, $this->getId());
			}
		}

		return parent::afterSave();
	}

}
