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
 * @package     Mageplaza_GiftCard
 * @copyright   Copyright (c) 2018 Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Productslider\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Mageplaza\Productslider\Model\Config\Source\ProductType;

/**
 * Class CommentContent
 * @package Mageplaza\Blog\Ui\Component\Listing\Columns
 */
class SliderType extends Column
{
    /**
     * @var ProductType
     */
    protected $productType;

    /**
     * SliderType constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ProductType $productType
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ProductType $productType,
        array $components = [],
        array $data = []
    ) {
        $this->productType = $productType;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$this->getData('name')])) {
                    $productType = $this->productType->getLabel($item[$this->getData('name')]);

                    $item[$this->getData('name')] = '<span>' . $productType . '</span>';
                }
            }
        }

        return $dataSource;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ProductType::NEW_PRODUCTS         => __('New Products'),
            ProductType::BEST_SELLER_PRODUCTS => __('Best Seller Products'),
            ProductType::FEATURED_PRODUCTS    => __('Featured Products'),
            ProductType::MOSTVIEWED_PRODUCTS  => __('Most Viewed Products'),
            ProductType::ONSALE_PRODUCTS      => __('On Sale Products'),
            ProductType::RECENT_PRODUCT       => __('Recent Products'),
            ProductType::CATEGORY             => __('Select By Category'),
            ProductType::CUSTOM_PRODUCTS      => __('Custom Specific Products'),
        ];
    }
}
