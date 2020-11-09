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

namespace Mageplaza\Productslider\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Mageplaza\Productslider\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $connection = $setup->getConnection();

        if (version_compare($context->getVersion(), '2.0.0', '<')) {
            if ($setup->tableExists('mageplaza_productslider_slider')) {
                $connection->dropTable($setup->getTable('mageplaza_productslider_slider'));
            }
            $table = $connection->newTable($setup->getTable('mageplaza_productslider_slider'))
                ->addColumn('slider_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ], 'Rule Id')
                ->addColumn('name', Table::TYPE_TEXT, 255, [], 'Name')
                ->addColumn('status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], 'Status')
                ->addColumn('title', Table::TYPE_TEXT, 255, [], 'Title')
                ->addColumn('description', Table::TYPE_TEXT, '64k', [], 'Description')
                ->addColumn('store_ids', Table::TYPE_TEXT, 255, [])
                ->addColumn('customer_group_ids', Table::TYPE_TEXT, 255, [])
                ->addColumn(
                    'limit_number',
                    Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Limit the number of products'
                )
                ->addColumn('location', Table::TYPE_TEXT, 255, [], 'Location')
                ->addColumn('time_cache', Table::TYPE_TEXT, 255, [], 'Cache Lifetime')
                ->addColumn('from_date', Table::TYPE_DATETIME, null, [], 'From Date')
                ->addColumn('to_date', Table::TYPE_DATETIME, null, [], 'To Date')
                ->addColumn('product_type', Table::TYPE_TEXT, 255, [], 'Type')
                ->addColumn('categories_ids', Table::TYPE_TEXT, 255, [])
                ->addColumn('product_ids', Table::TYPE_TEXT, 255, [])
                ->addColumn('display_additional', Table::TYPE_TEXT, 255, [], 'Display additional Information')
                ->addColumn('is_responsive', Table::TYPE_TEXT, 255, [], 'Responsive')
                ->addColumn('responsive_items', Table::TYPE_TEXT, 255, [], 'Max Items slider')
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Creation Time'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Update Time'
                )
                ->setComment('Product Slider Block');

            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}
