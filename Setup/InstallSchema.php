<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
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
 * @copyright   Copyright (c) 2017-2018 Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
	/**
	 * @param SchemaSetupInterface $setup
	 * @param ModuleContextInterface $context
	 * @throws \Zend_Db_Exception
	 */
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();
		if (!$installer->tableExists('mageplaza_productslider_slider')) {
			$table = $installer->getConnection()
				->newTable($installer->getTable('mageplaza_productslider_slider'))
				->addColumn('slider_id', Table::TYPE_INTEGER, null, [
					'identity' => true,
					'unsigned' => true,
					'nullable' => false,
					'primary'  => true
				], 'Rule Id')
				->addColumn('name', Table::TYPE_TEXT, 255, [], 'Name')
				->addColumn('status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], 'Status')
				->addColumn('title', Table::TYPE_TEXT, 255, [], 'Title')
				->addColumn('location', Table::TYPE_TEXT, 255, [], 'Location')
				->addColumn('time_cache', Table::TYPE_TEXT,  255, [],'Cache Lifetime')
				->addColumn('cache_last_time', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Cache Last Time')
				->addColumn('from_date', Table::TYPE_DATE, null, ['nullable' => true, 'default' => null], 'From')
				->addColumn('to_date', Table::TYPE_DATE, null, ['nullable' => true, 'default' => null], 'To')
				->addColumn('product_type', Table::TYPE_TEXT, 255, [], 'Type')
				->addColumn('categories_ids', Table::TYPE_TEXT, 255, [])
				->addColumn('product_ids', Table::TYPE_TEXT, 255, [])
				->addColumn('display_additional', Table::TYPE_TEXT, 255, [], 'Display additional Information')
				->addColumn('is_responsive', Table::TYPE_TEXT, 255, [], 'Responsive')
				->addColumn('responsive_items', Table::TYPE_TEXT, 255, [], 'Max Items slider')
				->addColumn('created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Creation Time')
				->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE], 'Update Time')
				->setComment('Product Slider Block');

			$installer->getConnection()->createTable($table);
		}

		if (!$installer->tableExists('mageplaza_productslider_slider_store')) {
			$table = $installer->getConnection()
				->newTable($installer->getTable('mageplaza_productslider_slider_store'))
				->addColumn('slider_id', Table::TYPE_INTEGER, null, [
					'unsigned' => true,
					'nullable' => false,
					'primary'  => true
				], 'Slider Id')
				->addColumn('store_id', Table::TYPE_SMALLINT, null, ['unsigned' => true, 'nullable' => false, 'primary' => true], 'Store Id')
				->addIndex($installer->getIdxName('mageplaza_productslider_slider_store', ['store_id']), ['store_id'])
				->addForeignKey(
					$installer->getFkName('mageplaza_productslider_slider_store', 'slider_id', 'mageplaza_productslider_slider', 'slider_id'),
					'slider_id',
					$installer->getTable('mageplaza_productslider_slider'),
					'slider_id',
					Table::ACTION_CASCADE
				)
				->addForeignKey(
					$installer->getFkName('mageplaza_productslider_slider_store', 'store_id', 'store', 'store_id'),
					'store_id',
					$installer->getTable('store'),
					'store_id',
					Table::ACTION_CASCADE
				)
				->setComment('Product Slider To Stores Relations');

			$installer->getConnection()->createTable($table);
		}

		$customerGroupTable  = $setup->getConnection()->describeTable($setup->getTable('customer_group'));
		$customerGroupIdType = $customerGroupTable['customer_group_id']['DATA_TYPE'] == 'int'
			? Table::TYPE_INTEGER : $customerGroupTable['customer_group_id']['DATA_TYPE'];

		if (!$installer->tableExists('mageplaza_productslider_slider_customer_group')) {
			$table = $installer->getConnection()
				->newTable($installer->getTable('mageplaza_productslider_slider_customer_group'))
				->addColumn('slider_id', Table::TYPE_INTEGER, null, [
					'unsigned' => true,
					'nullable' => false,
					'primary'  => true
				], 'Rule Id'
				)->addColumn('customer_group_id', $customerGroupIdType, null, [
					'unsigned' => true,
					'nullable' => false,
					'primary'  => true
				], 'Customer Group Id'
				)->addIndex(
					$installer->getIdxName('mageplaza_productslider_slider_customer_group', ['customer_group_id']),
					['customer_group_id']
				)->addForeignKey(
					$installer->getFkName('mageplaza_productslider_slider_customer_group', 'slider_id', 'mageplaza_productslider_slider', 'slider_id'),
					'slider_id',
					$installer->getTable('mageplaza_productslider_slider'),
					'slider_id',
					Table::ACTION_CASCADE
				)->addForeignKey(
					$installer->getFkName('mageplaza_productslider_slider_customer_group', 'customer_group_id', 'customer_group', 'customer_group_id'),
					'customer_group_id',
					$installer->getTable('customer_group'),
					'customer_group_id',
					Table::ACTION_CASCADE
				)->setComment('Product Slider To Customer Groups Relations');
			$installer->getConnection()->createTable($table);
		}

		$installer->endSetup();
	}
}
