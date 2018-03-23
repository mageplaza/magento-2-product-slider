<?php
/**
 * Created by PhpStorm.
 * User: tuvv
 * Date: 3/22/2018
 * Time: 11:50 AM
 */

namespace Mageplaza\ProductSlider\Model\ResourceModel\Report\Product;


class Collection extends \Magento\Reports\Model\ResourceModel\Product\Collection
{
    public function addViewsCount($from = '', $to = '')
    {
        /**
         * Getting event type id for catalog_product_view event
         */
        $eventTypes = $this->_eventTypeFactory->create()->getCollection();
        foreach ($eventTypes as $eventType) {
            if ($eventType->getEventName() == 'catalog_product_view') {
                $productViewEvent = (int)$eventType->getId();
                break;
            }
        }

        $this->getSelect()->reset()->from(
            ['report_table_views' => $this->getTable('report_event')],
            ['views' => 'COUNT(report_table_views.event_id)']
        )->join(
            ['e' => $this->getProductEntityTableName()],
            'e.entity_id = report_table_views.object_id'
        )->where(
            'report_table_views.event_type_id = ?',
            $productViewEvent
        )->group(
            'e.entity_id'
        )->order(
            'views ' . self::SORT_ORDER_DESC
        )->having(
            'COUNT(report_table_views.event_id) > ?',
            0
        );

        if ($from != '' && $to != '') {
            $this->getSelect()->where('logged_at >= ?', $from)->where('logged_at <= ?', $to);
        }

        return $this;
    }
}