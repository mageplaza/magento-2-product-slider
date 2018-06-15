<?php
/**
 * Created by PhpStorm.
 * User: nk
 * Date: 15/06/2018
 * Time: 15:18
 */

namespace Mageplaza\Productslider\Block\Adminhtml\Slider\Edit\Tab\Design;

use Magento\Store\Model\System\Store;
use Mageplaza\Productslider\Model\ResourceModel\SliderFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Stdlib\DateTime;
use Magento\Customer\Api\GroupRepositoryInterface;
use Mageplaza\Productslider\Model\Config\Source\ProductType;
use Mageplaza\Productslider\Model\Config\Source\Additional;


class Design extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;

    /**
     * Country options
     *
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $_booleanOptions;


    protected $resourceModelSliderFactory;

    protected $_groupRepository;

    protected $_searchCriteriaBuilder;

    protected $_objectConverter;

    protected $productType;

    protected $additional;

    /**
     * General constructor.
     * @param \Magento\Config\Model\Config\Source\Yesno $booleanOptions
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param Store $systemStore
     * @param array $data
     */
    public function __construct(
        Additional $additional,
        SliderFactory $resourceModelSliderFactory,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $objectConverter,
        ProductType $productType,
        \Magento\Config\Model\Config\Source\Yesno $booleanOptions,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Store $systemStore,
        array $data = []
    )
    {
        $this->additional = $additional;
        $this->resourceModelSliderFactory = $resourceModelSliderFactory;
        $this->_groupRepository = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_objectConverter = $objectConverter;
        $this->systemStore = $systemStore;
        $this->productType = $productType;
        $this->_booleanOptions = $booleanOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return \Magento\Backend\Block\Widget\Form\Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Mageplaza\Productslider\Model\Slider $slider */
        $slider = $this->_coreRegistry->registry('mageplaza_productslider_slider');
        $resourceModel = $this->resourceModelSliderFactory->create();

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slider_');
        $form->setFieldNameSuffix('slider');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Design'),
                'class' => 'fieldset-wide'
            ]
        );

        $fieldset->addField('is_responsive', 'select', [
                'name'    => 'is_responsive',
                'label'   => __('Is Responsive'),
                'title'   => __('Is Responsive'),
                'options' => [
                    '1' => __('Yes'),
                    '0' => __('No')
                ]
            ]
        );


























        $sliderData = $this->_session->getData('mageplaza_productslider_slider_data', true);
        if ($sliderData) {
            $slider->addData($sliderData);
        } else {
            if (!$slider->getId()) {
                $slider->addData($slider->getDefaultValues());
            }
        }
        $form->setValues($slider->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Design');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHidden()
    {
        return false;
    }
}
