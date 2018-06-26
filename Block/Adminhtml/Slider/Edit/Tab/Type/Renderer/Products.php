<?php

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Type\Renderer;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Registry;

/**
 * Class Product
 * @package Mageplaza\Blog\Block\Adminhtml\Post\Edit\Tab
 */
class Products extends Extended implements TabInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    public $productCollectionFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry;

    /**
     * Product constructor.
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Data $backendHelper,
        CollectionFactory $productCollectionFactory,
        array $data = []
    )
    {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Set grid params
     */
    public function _construct()
    {
        parent::_construct();

        $this->setId('productsGrid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);

        if ($this->getSlider()->getId()) {
            $this->setDefaultFilter(['in_products' => 1]);
        }
    }

    /**
     * @inheritdoc
     */
    protected function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('price');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_products', [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_product',
//                'values' => $this->_getSelectedProducts(),
                'value' => [1,2,3,4],
                'align' => 'center',
                'index' => 'entity_id'
            ]
        );
        $this->addColumn('entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'type' => 'number',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'names',
            [
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn('title', [
                'header' => __('Sku'),
                'index' => 'sku',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'index' => 'price',
                'width' => '50px',
            ]
        );

        return $this;



//        return parent::_prepareColumns();


    }

    /**
     * Retrieve selected Tags
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $productIds = [];
        foreach ($this->_collection as $item){
            $productIds[] = $item->getData('entity_id');
        }

        return $productIds;
    }


    /**
     * Retrieve selected Tags
     * @return array
     */
    public function getSelectedProducts()
    {
        $selected = $this->getPost()->getProductsPosition();
        if (!is_array($selected)) {
            $selected = [];
        } else {
            foreach ($selected as $key => $value) {
                $selected[$key] = ['position' => $value];
            }
        }

        return $selected;
    }

    /**
     * @param \Mageplaza\Blog\Model\Tag|\Magento\Framework\Object $item
     * @return string
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
//        var_dump($this->getSlider()->getId());
//        die('xxx');
        return $this->getUrl('*/*/productsGrid', ['slider_id' => $this->getSlider()->getId()]);
//        return $this->getUrl('*/*/productsgrid', ['_current' => true]);
    }

    /**
     * @return \Mageplaza\Blog\Model\Post
     */
    public function getSlider()
    {
        return $this->coreRegistry->registry('mageplaza_productslider_slider');
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    public function getTabUrl()
    {
        return $this->getUrl('mageplaza_productslider/slider/products', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return __('Products');
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax only';
    }
}
