<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Block\Adminhtml;

use Magenerds\WysiwygWidget\Model\WidgetInstance;
use Magento\Backend\Block\Widget\Context;
use Magento\Widget\Block\Adminhtml\Widget as BaseWidget;

/**
 * Class Widget
 *
 * @package     Magenerds\WysiwygWidget\Block\Adminhtml
 * @file        Widget.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
class Widget extends BaseWidget
{
    /**
     * @var WidgetInstance
     */
    protected $widgetInstance;

    /**
     * Widget constructor.
     *
     * @param Context $context
     * @param WidgetInstance $widgetInstance
     * @param array $data
     */
    public function __construct(
        Context $context,
        WidgetInstance $widgetInstance,
        array $data = []
    )
    {
        $this->widgetInstance = $widgetInstance;
        parent::__construct($context, $data);
    }

    /**
     * Sub-Constructor
     */
    protected function _construct()
    {
        // construct
        parent::_construct();

        // generate widget key
        $widgetKey = 'widget_' . uniqid();

        // set widget key
        $this->widgetInstance->setWidgetKey($widgetKey);

        // set button id and action
        $this->buttonList->update('save', 'onclick', $widgetKey . '.insertWidget()');
        $this->buttonList->update('reset', 'onclick', $widgetKey . '.closeModal()');

        // remove last form script
        array_pop($this->_formScripts);

        // add new form script
        $this->_formScripts[] = sprintf('
            require(["Magenerds_WysiwygWidget/js/wysiwyg/widget"], function(WidgetElement) {
                new WidgetElement(%s, %s, %s, %s, %s, %s);
            });
        ',
            json_encode($widgetKey),
            json_encode('widget_options_form_' . $widgetKey),
            json_encode('select_widget_type_' . $widgetKey),
            json_encode('widget_options_' . $widgetKey),
            json_encode($this->getUrl('adminhtml/*/loadOptions')),
            json_encode($this->getRequest()->getParam('widget_target_id'))
        );
    }
}
