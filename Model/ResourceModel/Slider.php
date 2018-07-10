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

use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\DateTime as Date;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Productslider\Model\Config\Source\ProductType;

/**
 * Class Slider
 * @package Mageplaza\Productslider\Model\ResourceModel
 */
class Slider extends AbstractDb
{
    const TBL_ATT_PRODUCT          = 'mageplaza_productslider_product_list';
    const NEW_PRODUCT_PATH         = 'Mageplaza\Productslider\Block\NewProducts';
    const BEST_SELLER_PATH         = 'Mageplaza\Productslider\Block\BestSellerProducts';
    const FEATURED_PRODUCTS_PATH   = 'Mageplaza\Productslider\Block\FeaturedProducts';
    const MOSTVIEWED_PRODUCTS_PATH = 'Mageplaza\Productslider\Block\MostViewedProducts';
    const ONSALE_PRODUCTS_PATH     = 'Mageplaza\Productslider\Block\OnSaleProduct';
    const RECENT_PRODUCT_PATH      = 'Mageplaza\Productslider\Block\RecentProducts';
    const WISHLIST_PRODUCT_PATH    = 'Mageplaza\Productslider\Block\WishlistProducts';
    const CATEGORYID_PATH          = 'Mageplaza\Productslider\Block\CategoryId';
    const CUSTOM_PRODUCT_PATH      = 'Mageplaza\Productslider\Block\CustomProducts';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Slider constructor.
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        HttpContext $httpContext,
        StoreManagerInterface $storeManager,
        DateTime $dateTime,
        Date $date,
        Context $context
    )
    {
        $this->httpContext  = $httpContext;
        $this->storeManager = $storeManager;
        $this->_dateTime    = $dateTime;
        $this->_date        = $date;

        parent::__construct($context);
    }

    /**
     * customer group rule config
     *
     * @param string $ruleId
     * @return array
     */
    public function getCustomerGroupByRuleId($ruleId)
    {
        $tableName = $this->getTable('mageplaza_productslider_slider_customer_group');
        $select    = $this->getConnection()->select()
            ->from($tableName, 'customer_group_id')
            ->where('slider_id = ?', $ruleId);

        return $this->getConnection()->fetchCol($select);
    }

    /**
     * store view slider config
     *
     * @param $sliderId
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

    /**
     * Update store view
     *
     * @param array $data
     * @param $sliderId
     */
    public function updateStore($data = [], $sliderId)
    {
        $dataInsert = [];
        foreach ($data as $storeId) {
            $dataInsert[] = [
                'slider_id' => $sliderId,
                'store_id'  => $storeId
            ];
        }
        $this->updateMultipleData('mageplaza_productslider_slider_store', $dataInsert);
    }

    /**
     * Update store view
     *
     * @param $tableName
     * @param array $data
     */
    public function updateMultipleData($tableName, $data = [])
    {
        $table = $this->getTable($tableName);
        if ($table && !empty($data)) {
            $this->getConnection()->insertMultiple($table, $data);
        }
    }

    /**
     * Update customer group
     *
     * @param array $data
     * @param $sliderId
     */
    public function updateCustomerGroup($data = [], $sliderId)
    {
        $dataInsert = [];
        foreach ($data as $customerGroupId) {
            $dataInsert[] = [
                'slider_id'         => $sliderId,
                'customer_group_id' => $customerGroupId
            ];
        }
        $this->updateMultipleData('mageplaza_productslider_slider_customer_group', $dataInsert);
    }

    /**
     * Delete Old Data
     *
     * @param $sliderId
     */
    public function deleteOldData($sliderId)
    {
        if ($sliderId) {
            $where = ['slider_id = ?' => $sliderId];
            $this->deleteMultipleData('mageplaza_productslider_slider_store', $where);
            $this->deleteMultipleData('mageplaza_productslider_slider_customer_group', $where);
        }
    }

    /**
     * Delete database
     *
     * @param string $tableName
     * @param array $where
     * @return void
     */
    public function deleteMultipleData($tableName, $where = [])
    {
        $table = $this->getTable($tableName);
        if ($table && !empty($where)) {
            $this->getConnection()->delete($table, $where);
        }
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSliderIds()
    {
        $adapter = $this->getConnection();
        $select  = $adapter->select()
            ->from(['main' => $this->getMainTable()])
            ->join(
                ['group' => $this->getTable('mageplaza_productslider_slider_customer_group')],
                'main.slider_id=group.slider_id',
                ['customer_group_id' => 'group.customer_group_id']
            )
            ->join(
                ['store' => $this->getTable('mageplaza_productslider_slider_store')],
                'main.slider_id=store.slider_id',
                ['store_id' => 'store.store_id']
            )
            ->where('customer_group_id = ?', $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP))
            ->where('store_id = 0 OR store_id = ?', $this->storeManager->getStore()->getId())
            ->where('from_date is null OR from_date <= ?', $this->_date->date())
            ->where('to_date is null OR to_date >= ?', $this->_date->date())
            ->where('status = ?', true);

        $ruleIds = array_unique($adapter->fetchCol($select));

        return $ruleIds;
    }

    /**
     * Get Block Path of Product Type
     *
     * @param $slider
     * @return string
     */
    public function getSliderProductType($slider)
    {
        $block       = '';
        $productType = $slider->getProductType();

        switch ($productType) {
            case ProductType::NEW_PRODUCTS :
                $block = self::NEW_PRODUCT_PATH;
                break;
            case ProductType::BEST_SELLER_PRODUCTS :
                $block = self::BEST_SELLER_PATH;
                break;
            case ProductType::FEATURED_PRODUCTS :
                $block = self::FEATURED_PRODUCTS_PATH;
                break;
            case ProductType::MOSTVIEWED_PRODUCTS :
                $block = self::MOSTVIEWED_PRODUCTS_PATH;
                break;
            case ProductType::ONSALE_PRODUCTS :
                $block = self::ONSALE_PRODUCTS_PATH;
                break;
            case ProductType::RECENT_PRODUCT :
                $block = self::RECENT_PRODUCT_PATH;
                break;
            case ProductType::WISHLIST_PRODUCT :
                $block = self::WISHLIST_PRODUCT_PATH;
                break;
            case ProductType::CATEGORYID :
                $block = self::CATEGORYID_PATH;
                break;
            case ProductType::CUSTOM_PRODUCTS :
                $block = self::CUSTOM_PRODUCT_PATH;
                break;
        }

        return $block;
    }

    /**
     * Get Slider Type and Location
     *
     * @param $slider
     * @return mixed
     */
    public function getSliderLocation($slider)
    {
        $location          = explode('.', $slider->getLocation());
        $data['page_type'] = $location[0];
        $data['location']  = $location[1];

        return $data;
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
}
