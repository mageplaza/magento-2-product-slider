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

namespace Mageplaza\Productslider\Block;

use Exception;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\Widget\Block\BlockInterface;
use Mageplaza\Productslider\Helper\Data;
use Mageplaza\Productslider\Model\Config\Source\Additional;

/**
 * Class AbstractSlider
 * @package Mageplaza\Productslider\Block
 */
abstract class AbstractSlider extends AbstractProduct implements BlockInterface, IdentityInterface
{
    /**
     * @var DateTime
     */
    protected $_date;
    /**
     * @var Data
     */
    protected $_helperData;
    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var Visibility
     */
    protected $_catalogProductVisibility;
    /**
     * @var HttpContext
     */
    protected $httpContext;
    /**
     * @var EncoderInterface|null
     */
    protected $urlEncoder;
    /**
     * @var Grouped
     */
    protected $grouped;
    /**
     * @var Configurable
     */
    protected $configurable;
    /**
     * @var
     */
    protected $rendererListBlock;
    /**
     * @var
     */
    private $priceCurrency;
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * AbstractSlider constructor.
     *
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param DateTime $dateTime
     * @param Data $helperData
     * @param HttpContext $httpContext
     * @param EncoderInterface $urlEncoder
     * @param Grouped $grouped
     * @param Configurable $configurable
     * @param LayoutFactory $layoutFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        DateTime $dateTime,
        Data $helperData,
        HttpContext $httpContext,
        EncoderInterface $urlEncoder,
        Grouped $grouped,
        Configurable $configurable,
        LayoutFactory $layoutFactory,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_date                     = $dateTime;
        $this->_helperData               = $helperData;
        $this->httpContext               = $httpContext;
        $this->urlEncoder                = $urlEncoder;
        $this->grouped                   = $grouped;
        $this->configurable              = $configurable;
        $this->layoutFactory             = $layoutFactory;

        parent::__construct($context, $data);
    }

    /**
     * Get Key pieces for caching block content
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCacheKeyInfo()
    {
        return [
            'MAGEPLAZA_PRODUCT_SLIDER',
            $this->getPriceCurrency()->getCurrency()->getCode(),
            $this->_storeManager->getStore()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
            $this->getSliderId()
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();

        $this->addData([
            'cache_lifetime' => $this->getSlider() ? $this->getSlider()->getTimeCache() : 86400,
            'cache_tags'     => [Product::CACHE_TAG]
        ]);

        $this->setTemplate('Mageplaza_Productslider::productslider.phtml');
    }

    /**
     * Get Slider Id
     * @return string
     */
    public function getSliderId()
    {
        if ($this->getSlider()) {
            return $this->getSlider()->getSliderId();
        }

        return uniqid('-', false);
    }

    /**
     * @return Data
     */
    public function getHelperData()
    {
        return $this->_helperData;
    }

    /**
     * Get post parameters.
     *
     * @param Product $product
     *
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product);

        return [
            'action' => $url,
            'data'   => [
                'product'                               => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlEncoder->encode($url),
            ]
        ];
    }

    /**
     * @return bool
     */
    public function canShowPrice()
    {
        return in_array(Additional::SHOW_PRICE, $this->getDisplayAdditional(), true);
    }

    /**
     * @return array|mixed
     */
    public function getDisplayAdditional()
    {
        if ($this->getSlider()) {
            $display = $this->getSlider()->getDisplayAdditional();
        } else {
            $display = $this->_helperData->getModuleConfig('general/display_information');
        }

        if (!is_array($display)) {
            $display = explode(',', $display);
        }

        return $display;
    }

    /**
     * @param Product $product
     * @param null $priceType
     * @param string $renderZone
     * @param array $arguments
     *
     * @return string
     * @throws LocalizedException
     */
    public function getProductPriceHtml(
        Product $product,
        $priceType = null,
        $renderZone = Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['price_id']              = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container']     = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            ? $arguments['display_minimal_price']
            : true;

        /** @var Render $priceRender */
        $priceRender = $this->getPriceRender();
        if (!$priceRender) {
            $priceRender = $this->getLayout()->createBlock(
                Render::class,
                'product.price.render.default',
                ['data' => ['price_render_handle' => 'catalog_product_prices']]
            );
        }

        return $priceRender->render(
            FinalPrice::PRICE_CODE,
            $product,
            $arguments
        );
    }

    /**
     * @return bool|\Magento\Framework\View\Element\BlockInterface
     * @throws LocalizedException
     */
    protected function getPriceRender()
    {
        return $this->getLayout()->getBlock('product.price.render.default');
    }

    /**
     * @return mixed
     */
    private function getPriceCurrency()
    {
        if ($this->priceCurrency === null) {
            $this->priceCurrency = ObjectManager::getInstance()
                ->get(PriceCurrencyInterface::class);
        }

        return $this->priceCurrency;
    }

    /**
     * @return bool
     */
    public function canShowReview()
    {
        return in_array(Additional::SHOW_REVIEW, $this->getDisplayAdditional(), true);
    }

    /**
     * @return bool
     */
    public function canShowAddToCart()
    {
        return in_array(Additional::SHOW_CART, $this->getDisplayAdditional(), true);
    }

    /**
     * Get Slider Title
     *
     * @return mixed|string
     */
    public function getTitle()
    {
        if ($title = $this->hasData('title')) {
            return $this->getData('title');
        }

        if ($this->getSlider()) {
            return $this->getSlider()->getTitle();
        }

        return '';
    }

    /**
     * Get Slider Description
     *
     * @return mixed|string
     */
    public function getDescription()
    {
        if ($this->hasData('description')) {
            return $this->getData('description');
        }

        if ($this->getSlider()) {
            return $this->getSlider()->getDescription();
        }

        return '';
    }

    /**
     * @return string
     */
    public function getAllOptions()
    {
        $sliderOptions = '';
        $allConfig     = $this->_helperData->getModuleConfig('slider_design');

        foreach ($allConfig as $key => $value) {
            if ($key === 'item_slider') {
                if (empty($this->getSlider())) {
                    $sliderOptions .= $this->_helperData->getResponseValue();
                } else {
                    $sliderOptions .= $this->getResponsiveConfig();
                }
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
     * @return string
     */
    public function getResponsiveConfig()
    {
        $slider = $this->getSlider();
        if ($slider && $slider->getIsResponsive()) {
            try {
                if ($slider->getIsResponsive() === '2') {
                    return $this->_helperData->getResponseValue();
                }

                $responsiveConfig = $slider->getResponsiveItems()
                    ? $this->_helperData->unserialize($slider->getResponsiveItems())
                    : [];
            } catch (Exception $e) {
                $responsiveConfig = [];
            }

            if (empty($responsiveConfig)) {
                return '';
            }

            $responsiveOptions = '';
            foreach ($responsiveConfig as $config) {
                if (!empty($config['size']) && !empty($config['items'])) {
                    $responsiveOptions .= $config['size'] . ':{items:' . $config['items'] . '},';
                }
            }
            $responsiveOptions = rtrim($responsiveOptions, ',');

            return 'responsive:{' . $responsiveOptions . '}';
        }

        return '';
    }

    /**
     * Get End of Day Date
     *
     * @return string
     */
    public function getEndOfDayDate()
    {
        return $this->_date->date(null, '23:59:59');
    }

    /**
     * Get Start of Day Date
     *
     * @return string
     */
    public function getStartOfDayDate()
    {
        return $this->_date->date(null, '0:0:0');
    }

    /**
     * Get Store Id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        $identities = [];
        if ($this->getProductCollection()) {
            foreach ($this->getProductCollection() as $product) {
                if ($product instanceof IdentityInterface) {
                    $identities += $product->getIdentities();
                }
            }
        }

        return $identities ?: [Product::CACHE_TAG];
    }

    /**
     * @return mixed
     */
    abstract public function getProductCollection();

    /**
     * Get Product Count is displayed
     *
     * @return mixed
     */
    public function getProductsCount()
    {
        if ($this->hasData('products_count')) {
            return $this->getData('products_count');
        }

        if ($this->getSlider()) {
            return $this->getSlider()->getLimitNumber() ?: 5;
        }

        return 5;
    }

    /**
     * @param $collection
     *
     * @return array
     */
    public function getProductParentIds($collection)
    {
        $productIds = [];

        foreach ($collection as $product) {
            if (isset($product->getData()['entity_id'])) {
                $productId = $product->getData()['entity_id'];
            } else {
                $productId = $product->getProductId();
            }

            $parentIdsGroup  = $this->grouped->getParentIdsByChild($productId);
            $parentIdsConfig = $this->configurable->getParentIdsByChild($productId);

            if (!empty($parentIdsGroup)) {
                $productIds[] = $parentIdsGroup;
            } elseif (!empty($parentIdsConfig)) {
                $productIds[] = $parentIdsConfig[0];
            } else {
                $productIds[] = $productId;
            }
        }

        return $productIds;
    }

    /**
     * @return bool|\Magento\Framework\View\Element\BlockInterface|\Magento\Framework\View\Element\RendererList
     * @throws LocalizedException
     */
    protected function getDetailsRendererList()
    {
        if (empty($this->rendererListBlock)) {
            $layout = $this->layoutFactory->create(['cacheable' => false]);
            $layout->getUpdate()->addHandle('catalog_widget_product_list')->load();
            $layout->generateXml();
            $layout->generateElements();

            $this->rendererListBlock = $layout->getBlock('category.product.type.widget.details.renderers');
        }

        return $this->rendererListBlock;
    }
}
