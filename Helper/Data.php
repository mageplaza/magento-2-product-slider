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

namespace Mageplaza\Productslider\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData;
use Mageplaza\Productslider\Model\ResourceModel\Slider\Collection;
use Mageplaza\Productslider\Model\SliderFactory;
use Zend_Serializer_Exception;

/**
 * Class Data
 * @package Mageplaza\Productslider\Helper
 */
class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'productslider';

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @var SliderFactory
     */
    protected $sliderFactory;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param DateTime $date
     * @param HttpContext $httpContext
     * @param SliderFactory $sliderFactory
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        DateTime $date,
        HttpContext $httpContext,
        SliderFactory $sliderFactory
    ) {
        $this->date = $date;
        $this->httpContext = $httpContext;
        $this->sliderFactory = $sliderFactory;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getActiveSliders()
    {
        $customerId = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP);
        /** @var Collection $collection */
        $collection = $this->sliderFactory->create()
            ->getCollection()
            ->addFieldToFilter('customer_group_ids', ['finset' => $customerId])
            ->addFieldToFilter('status', 1);

        $collection->getSelect()
            ->where('FIND_IN_SET(0, store_ids) OR FIND_IN_SET(?, store_ids)', $this->storeManager->getStore()->getId())
            ->where('from_date is null OR from_date <= ?', $this->date->date())
            ->where('to_date is null OR to_date >= ?', $this->date->date());

        return $collection;
    }

    /**
     * Retrieve all configuration options for product slider
     *
     * @return string
     */
    public function getAllOptions()
    {
        $sliderOptions = '';
        $allConfig = $this->getModuleConfig('slider_design');
        foreach ($allConfig as $key => $value) {
            if ($key === 'item_slider') {
                $sliderOptions .= $this->getResponseValue();
            } elseif ($key !== 'responsive') {
                if (in_array($key, ['loop', 'nav', 'dots', 'lazyLoad', 'autoplay', 'autoplayHoverPause'])) {
                    $value = $value ? 'true' : 'false';
                }
                $sliderOptions .= $key . ':' . $value . ',';
            }
        }

        return '{' . $sliderOptions . '}';
    }

    /**
     * Retrieve responsive values for product slider
     *
     * @return string
     * @throws Zend_Serializer_Exception
     */
    public function getResponseValue()
    {
        $responsiveOptions = '';
        $responsiveConfig = $this->getModuleConfig('slider_design/responsive')
            ? $this->unserialize($this->getModuleConfig('slider_design/item_slider'))
            : [];

        if (empty($responsiveConfig)) {
            return '';
        }

        foreach ($responsiveConfig as $config) {
            if (!empty($config['size']) && !empty($config['items'])) {
                $responsiveOptions .= $config['size'] . ':{items:' . $config['items'] . '},';
            }
        }

        $responsiveOptions = rtrim($responsiveOptions, ',');

        return 'responsive:{' . $responsiveOptions . '}';
    }
}
