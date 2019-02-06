<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Block\Adminhtml\Widget;

use Magenerds\WysiwygWidget\Block\Widget\Editor as EditorBlock;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Element;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Editor as EditorElement;
use Magento\Framework\Data\Form\Element\Factory;

/**
 * Class Editor
 *
 * @package     Magenerds\WysiwygWidget\Block\Adminhtml\Widget
 * @file        Editor.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
class Editor extends Element
{
    /**
     * @var Config
     */
    protected $wysiwygConfig;

    /**
     * @var Factory
     */
    protected $factoryElement;

    /**
     * Editor constructor.
     *
     * @param Context $context
     * @param Factory $factoryElement
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Factory $factoryElement,
        Config $wysiwygConfig,
        $data = []
    )
    {
        parent::__construct($context, $data);
        $this->factoryElement = $factoryElement;
        $this->wysiwygConfig = $wysiwygConfig;
    }

    /**
     * Prepare chooser element HTML
     *
     * @param AbstractElement $element Form Element
     * @return AbstractElement
     */
    public function prepareElementHtml(AbstractElement $element)
    {
        // create editor
        /** @var EditorElement $editor */
        /** @noinspection PhpUndefinedMethodInspection */
        $editor = $this->factoryElement->create('editor', ['data' => $element->getData()])
            ->setData('label', '')
            ->setForm($element->getForm());
        $this->setEditorConfig($editor);

        // add required class
        /** @noinspection PhpUndefinedMethodInspection */
        if ($element->getRequired()) {
            /** @noinspection PhpUndefinedMethodInspection */
            $editor->addClass('required-entry');
        }

        // as we're loading the editor after the page has been completely loaded, we need to change onLoad() to ready()
        /** @noinspection PhpUndefinedMethodInspection */
        $element->setData(
            'after_element_html',
            $this->getAfterElementHtml() .
            str_replace('jQuery(window).on("load", ', 'jQuery(', $editor->getElementHtml())
        );

        // return element
        return $element;
    }

    /**
     * Get skipped widgets
     *
     * @return array
     */
    protected function getSkippedWidgets()
    {
        return [EditorBlock::class];
    }

    /**
     * Set editor configuration
     *
     * @param EditorElement $editor
     * @return EditorElement
     */
    protected function setEditorConfig(EditorElement $editor)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $editor
            ->setData('wysiwyg', true)
            ->setData('config', $this->wysiwygConfig->getConfig([
                'skip_widgets' => $this->getSkippedWidgets(),
                'add_widgets' => true,
            ]));
    }

    /**
     * Get element's after html
     *
     * @return string
     */
    protected function getAfterElementHtml()
    {
        return <<<HTML
    <style>
        .admin__field-control.control .control-value {
            display: none !important;
        }
    </style>
HTML;
    }
}
