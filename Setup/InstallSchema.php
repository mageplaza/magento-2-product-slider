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
namespace Mageplaza\Productslider\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('mageplaza_productslider_slider')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('mageplaza_productslider_slider')
            )
            ->addColumn(
                'slider_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'Slider ID'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Slider Name'
            )
            ->addColumn(
                'store_views',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                [],
                'Slider Store View'
            )
            ->addColumn(
                'active_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'Slider Active From'
            )
            ->addColumn(
                'active_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Slider Active To'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                [],
                'Slider Status'
            )
            ->addColumn(
                'serialized_data',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                [],
                'Slider Data'
            )

            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Slider Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Slider Updated At'
            )
            ->setComment('Slider Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('mageplaza_productslider_slider'),
                $setup->getIdxName(
                    $installer->getTable('mageplaza_productslider_slider'),
                    ['name','active_to','serialized_data'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name','active_to','serialized_data'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}
