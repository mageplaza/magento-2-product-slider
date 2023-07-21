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

namespace Mageplaza\Productslider\Plugin\Block\Widget;

use Magento\Cms\Block\Widget\Block as WidgetBlock;
use Mageplaza\Productslider\Helper\Data as HelperData;
use Magento\Framework\App\RequestInterface;

/**
 * Class Block
 * @package Mageplaza\Productslider\Plugin\Block\Widget
 */
class Block
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param HelperData $helperData
     * @param RequestInterface $request
     */
    public function __construct(
        HelperData $helperData,
        RequestInterface $request
    ) {
        $this->helperData = $helperData;
        $this->request    = $request;
    }

    /**
     * @param WidgetBlock $subject
     * @param $result
     *
     * @return mixed|void
     */
    public function afterToHtml(WidgetBlock $subject, $result)
    {
        if ($this->helperData->isEnabled() && $this->request->getFullActionName() === 'cms_index_index') {
            return $result;
        }
    }
}
