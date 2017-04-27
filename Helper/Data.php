<?php
namespace Mageplaza\Productslider\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\ObjectManagerInterface;
use Mageplaza\Core\Helper\AbstractData as CoreHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Search\Model\Query as SearchQuery;
use Magento\Search\Model\QueryFactory;
use \Psr\Log\LoggerInterface;

/**
 * Search helper
 */
class Data extends CoreHelper
{
	/**
	 * @var array
	 */
	protected $_suggestData = null;

	/**
	 * Query object
	 *
	 * @var SearchQuery
	 */
	protected $_query;

	/**
	 * Query string
	 *
	 * @var string
	 */
	protected $_queryText;

	/**
	 * Note messages
	 *
	 * @var array
	 */
	protected $_messages = [];

	/**
	 * Magento string lib
	 *
	 * @var String
	 */
	protected $string;

	/**
	 * Core store config
	 *
	 * @var ScopeConfigInterface
	 */
	protected $_scopeConfig;

	/**
	 * Query factory
	 *
	 * @var QueryFactory
	 */
	protected $_queryFactory;

	/**
	 * @var Escaper
	 */
	protected $_escaper;

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;

	protected $_priceHelper;

	/**
	 * link to field config number_items_slider_screen_size_1000
	 */
	const NUMBER_ITEMS_SLIDER_SCREEN_SIZE_1000 = 'productslider_setting/slider/number_items_slider_screen_size_1000';

	/**
	 * link to field config number_items_slider_screen_size_600
	 */
	const NUMBER_ITEMS_SLIDER_SCREEN_SIZE_600 = 'productslider_setting/slider/number_items_slider_screen_size_600';

	/**
	 * link to field config number_items_slider_screen_size_0
	 */
	const NUMBER_ITEMS_SLIDER_SCREEN_SIZE_0 = 'productslider_setting/slider/number_items_slider_screen_size_0';


	/**
	 * link to field config loop_slider
	 */
	const LOOP_SLIDER = 'productslider_setting/slider/loop_slider';

	/**
	 *link to field config margin_between_items
	 */
	const MARGIN_BETWEEN_ITEMS = 'productslider_setting/slider/margin_between_items';

	/**
	 * "Enable Module" from system config
	 */
	const GENERAL_IS_ENABLED = 'productslider_setting/slider/is_enabled';

	protected $objectManager;

	public function __construct(
		Context $context,
		StringUtils $string,
		QueryFactory $queryFactory,
		Escaper $escaper,
		StoreManagerInterface $storeManager,
		ObjectManagerInterface $objectManager,
		\Magento\Framework\Pricing\Helper\Data $priceHelper
	)
	{
		$this->string        = $string;
		$this->_scopeConfig  = $context->getScopeConfig();
		$this->_queryFactory = $queryFactory;
		$this->_escaper      = $escaper;
		$this->_storeManager = $storeManager;
		$this->logger        = $context->getLogger();
		$this->_priceHelper  = $priceHelper;
		$this->objectManager   = $objectManager;

		parent::__construct($context, $objectManager, $storeManager);
	}

	/**
	 * Is a minimum query length
	 *
	 * @return bool
	 */

	public function getEscapedQueryText()
	{
		return $this->_escaper->escapeHtml($this->_queryFactory->get()->getQueryText());
	}

	/**
	 * Retrieve result page url and set "secure" param to avoid confirm
	 * message when we submit form from secure page to unsecure
	 *
	 * @param   string $query
	 * @return  string
	 */
	public function getResultUrl($query = null)
	{
		return $this->_getUrl(
			'catalogsearch/result',
			['_query' => [QueryFactory::QUERY_VAR_NAME => $query], '_secure' => $this->_request->isSecure()]
		);
	}

	/**
	 * Get config of product slider
	 * @param $configPath
	 * @param null $store
	 * @return mixed
	 */
	public function getSliderConfig($configPath, $store = null)
	{

		return $this->_scopeConfig->getValue(
			$configPath,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE,
			$store
		);
	}

	public function isEnabled($store = null)
	{
		$isModuleEnabled       = $this->isModuleEnabled();
		$isModuleOutputEnabled = $this->isModuleOutputEnabled();

		return $isModuleOutputEnabled && $isModuleEnabled && $this->getSliderConfig(self::GENERAL_IS_ENABLED, $store);
	}

	public function isModuleEnabled()
	{
		$moduleName = "Mageplaza_Productslider";

		return $this->_moduleManager->isEnabled($moduleName);
	}

	/**
	 * @return string
	 */
	public function getQueryParamName()
	{
		return QueryFactory::QUERY_VAR_NAME;
	}

	/**
	 * get js file url
	 * @return string
	 */

}
