<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mageplaza\Productslider\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
	/**
	 * EAV setup factory
	 *
	 * @var EavSetupFactory
	 */
	private $eavSetupFactory;

	/**
	 * Init
	 *
	 * @param EavSetupFactory $eavSetupFactory
	 */
	public function __construct(EavSetupFactory $eavSetupFactory)
	{
		$this->eavSetupFactory = $eavSetupFactory;
	}


	/**
	 * create featured product attribute
	 * {@inheritdoc}
	 */

	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
		$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
		$eavSetup->addAttribute(
			\Magento\Catalog\Model\Product::ENTITY,
			'is_featured',
			[
				'type' => 'int',
				'backend' => '',
				'frontend' => '',
				'label' => 'Featured Product',
				'input' => 'select',
				'class' => '',
				'source' => 'Mageplaza\Productslider\Model\Slider\Source\FeaturedProducts',
				'sort_order' => 3,
				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => false,
				'user_defined' => false,
				'default' => '0',
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => true,
				'used_in_product_listing' => true,
				'unique' => false,
				'apply_to' => 'simple,virtual,bundle,downloadable,grouped,configurable'
			]
		);
	}
/**
 * delete attibute programmatically
 */
//	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
//	{
//		$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
//		$eavSetup->removeAttribute(
//			\Magento\Catalog\Model\Product::ENTITY,
//			'is_featured');
//	}
}