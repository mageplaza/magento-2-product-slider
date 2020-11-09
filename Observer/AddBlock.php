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

namespace Mageplaza\Productslider\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Layout;
use Mageplaza\Productslider\Helper\Data;
use Mageplaza\Productslider\Model\Config\Source\ProductType;

/**
 * Class AddBlock
 * @package Mageplaza\AutoRelated\Observer
 */
class AddBlock implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var ProductType
     */
    protected $productType;

    /**
     * AddBlock constructor.
     *
     * @param RequestInterface $request
     * @param Data $helperData
     * @param ProductType $productType
     */
    public function __construct(
        RequestInterface $request,
        Data $helperData,
        ProductType $productType
    ) {
        $this->request = $request;
        $this->helperData = $helperData;
        $this->productType = $productType;
    }

    /**
     * @inheritdoc
     */
    public function execute(Observer $observer)
    {
        if (!$this->helperData->isEnabled()) {
            return $this;
        }

        $type = array_search($observer->getEvent()->getElementName(), [
            'content' => 'content',
            'sidebar' => 'catalog.leftnav'
        ]);
        if ($type !== false) {
            /** @var Layout $layout */
            $layout = $observer->getEvent()->getLayout();
            $fullActionName = $this->request->getFullActionName();
            $output = $observer->getTransport()->getOutput();
            foreach ($this->helperData->getActiveSliders() as $slider) {
                [$pageType, $location] = explode('.', $slider->getLocation());
                if ($fullActionName == $pageType || $pageType == 'allpage') {
                    $content = $layout->createBlock($this->productType->getBlockMap($slider->getProductType()))
                        ->setSlider($slider)
                        ->toHtml();

                    if (strpos($location, $type) !== false) {
                        if (strpos($location, 'top') !== false) {
                            $output = "<div id=\"mageplaza-productslider-block-before-{$type}-{$slider->getId()}\">$content</div>" . $output;
                        } else {
                            $output .= "<div id=\"mageplaza-productslider-block-after-{$type}-{$slider->getId()}\">$content</div>";
                        }
                    }
                }
            }
            $observer->getTransport()->setOutput($output);
        }

        return $this;
    }
}
