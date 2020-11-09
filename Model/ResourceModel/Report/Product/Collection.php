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

namespace Mageplaza\Productslider\Model\ResourceModel\Report\Product;

use Magento\Reports\Model\ResourceModel\Product\Collection as ReportCollection;

/**
 * Class Collection
 * @package Mageplaza\Productslider\Model\ResourceModel\Report\Product
 */
class Collection extends ReportCollection
{
    /**
     * @param string $from
     * @param string $to
     *
     * @return $this
     */
    public function addViewsCount($from = '', $to = '')
    {
        $this->getSelect()->reset()
            ->from(
                ['report_table_views' => $this->getTable('report_event')],
                ['views' => 'COUNT(report_table_views.event_id)']
            )
            ->join(
                ['e' => $this->getProductEntityTableName()],
                'e.entity_id = report_table_views.object_id'
            )
            ->group('e.entity_id')
            ->order('views ' . self::SORT_ORDER_DESC)
            ->having('COUNT(report_table_views.event_id) > ?', 0);

        /**
         * Getting event type id for catalog_product_view event
         */
        $eventTypes = $this->_eventTypeFactory->create()->getCollection();
        foreach ($eventTypes as $eventType) {
            if ($eventType->getEventName() == 'catalog_product_view') {
                $this->getSelect()->where('report_table_views.event_type_id = ?', (int)$eventType->getId());
                break;
            }
        }

        if ($from != '' && $to != '') {
            $this->getSelect()->where('logged_at >= ?', $from)->where('logged_at <= ?', $to);
        }

        return $this;
    }
}
