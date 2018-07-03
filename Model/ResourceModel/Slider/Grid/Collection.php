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

namespace Mageplaza\Productslider\Model\ResourceModel\Slider\Grid;

use Mageplaza\Productslider\Model\ResourceModel\Slider;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Collection extends Slider\Collection implements SearchResultInterface
{
	/**
	 * Aggregations
	 *
	 * @var \Magento\Framework\Search\AggregationInterface
	 */
	protected $_aggregations;

	/**
	 * Collection constructor.
	 * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
	 * @param \Psr\Log\LoggerInterface $logger
	 * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
	 * @param \Magento\Framework\Event\ManagerInterface $eventManager
	 * @param \Magento\Framework\DB\Adapter\AdapterInterface $mainTable
	 * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $eventPrefix
	 * @param $eventObject
	 * @param $resourceModel
	 * @param string $model
	 * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
	 * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
	 */
	public function __construct(
		EntityFactoryInterface $entityFactory,
		LoggerInterface $logger,
		FetchStrategyInterface $fetchStrategy,
		ManagerInterface $eventManager,
		$mainTable,
		$eventPrefix,
		$eventObject,
		$resourceModel,
		$model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',
		AdapterInterface $connection = null,
		AbstractDb $resource = null
	)
	{
		parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);

		$this->_eventPrefix = $eventPrefix;
		$this->_eventObject = $eventObject;
		$this->_init($model, $resourceModel);
		$this->setMainTable($mainTable);
	}


	/**
	 * @return \Magento\Framework\Search\AggregationInterface
	 */
	public function getAggregations()
	{
		return $this->_aggregations;
	}

	/**
	 * @param \Magento\Framework\Search\AggregationInterface $aggregations
	 * @return $this
	 */
	public function setAggregations($aggregations)
	{
		$this->_aggregations = $aggregations;
	}


	/**
	 * Retrieve all ids for collection
	 * Backward compatibility with EAV collection
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function getAllIds($limit = null, $offset = null)
	{
		return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
	}

	/**
	 * Get search criteria.
	 *
	 * @return \Magento\Framework\Api\SearchCriteriaInterface|null
	 */
	public function getSearchCriteria()
	{
		return null;
	}

	/**
	 * Set search criteria.
	 *
	 * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
	 * @return $this
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
	{
		return $this;
	}

	/**
	 * Get total count.
	 *
	 * @return int
	 */
	public function getTotalCount()
	{
		return $this->getSize();
	}

	/**
	 * Set total count.
	 *
	 * @param int $totalCount
	 * @return $this
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function setTotalCount($totalCount)
	{
		return $this;
	}

	/**
	 * Set items list.
	 *
	 * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
	 * @return $this
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function setItems(array $items = null)
	{
		return $this;
	}

}
