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
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
//        $values['status'] = '1';
        return $values;
    }

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
//
//        $this->getMatchingProductIds();
//        $this->getResource()->deleteMultipleData('mageplaza_autorelated_actions_index', ['rule_id = ?' => $this->getId()]);
//        if (!empty($this->dataProductIds) && is_array($this->dataProductIds)) {
//            $this->getResource()->updateMultipleData('mageplaza_autorelated_actions_index', $this->dataProductIds);
//        }

        return parent::afterSave();
    }
}
