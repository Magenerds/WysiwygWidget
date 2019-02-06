<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Block\Adminhtml\Widget;

use Magenerds\WysiwygWidget\Model\WidgetInstance;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form as DataForm;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use /** @noinspection PhpDeprecationInspection */
    Magento\Framework\Registry;
use Magento\Widget\Block\Adminhtml\Widget\Form as BaseForm;
use /** @noinspection PhpUndefinedClassInspection */
    Magento\Widget\Model\WidgetFactory;

/**
 * Class Form
 *
 * @package     Magenerds\WysiwygWidget\Block\Adminhtml\Widget
 * @file        Form.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
class Form extends BaseForm
{
    /**
     * @var WidgetInstance
     */
    protected $widgetInstance;
    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /** @noinspection PhpUndefinedClassInspection */
    /**
     * Form constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param WidgetFactory $widgetFactory
     * @param DataObjectFactory $dataObjectFactory
     * @param WidgetInstance $widgetInstance
     * @param array $data
     */
    public function __construct(
        /** @noinspection PhpDeprecationInspection PhpUndefinedClassInspection */
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        WidgetFactory $widgetFactory,
        DataObjectFactory $dataObjectFactory,
        WidgetInstance $widgetInstance,
        array $data = []
    )
    {
        $this->dataObjectFactory = $dataObjectFactory;
        $this->widgetInstance = $widgetInstance;
        parent::__construct($context, $registry, $formFactory, $widgetFactory, $data);
    }

    /**
     * Form with widget to select
     */
    protected function _prepareForm()
    {
        /** @var DataForm $form */
        /** @noinspection PhpUnhandledExceptionInspection */
        $form = $this->_formFactory->create();

        // define field set
        $fieldSet = $form->addFieldset('base_fieldset', ['legend' => __('Widget')]);

        // retrieve widget key
        $widgetKey = $this->widgetInstance->getWidgetKey();

        // add new field
        $fieldSet->addField(
            'select_widget_type_' . $widgetKey,
            'select',
            [
                'label' => __('Widget Type'),
                'title' => __('Widget Type'),
                'name' => 'widget_type',
                'required' => true,
                'onchange' => "$widgetKey.validateField()",
                'options' => $this->_getWidgetSelectOptions(),
                'after_element_html' => $this->_getWidgetSelectAfterHtml()
            ]
        );

        // set form information
        /** @noinspection PhpUndefinedMethodInspection */
        $form->setUseContainer(true);
        /** @noinspection PhpUndefinedMethodInspection */
        $form->setId('widget_options_form' . '_' . $widgetKey);
        /** @noinspection PhpUndefinedMethodInspection */
        $form->setMethod('post');
        /** @noinspection PhpUndefinedMethodInspection */
        $form->setAction($this->getUrl('adminhtml/*/buildWidget'));
        $this->setForm($form);
    }

    /**
     * Retrieve Additional Element Types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        /** @var DataObject $result */
        $result = $this->dataObjectFactory->create(['types' => []]);
        $this->_eventManager->dispatch('widget_form_additional_element_types', ['result' => $result]);
        return (array)$result ?: [];
    }
}
