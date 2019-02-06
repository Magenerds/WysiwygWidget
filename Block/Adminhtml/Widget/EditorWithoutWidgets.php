<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Block\Adminhtml\Widget;

use Magento\Framework\Data\Form\Element\Editor as EditorElement;

/**
 * Class EditorWithoutWidgets
 *
 * @package     Magenerds\WysiwygWidget\Block\Adminhtml\Widget
 * @file        EditorWithoutWidgets.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
class EditorWithoutWidgets extends Editor
{
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
                'add_widgets' => false,
            ]));
    }
}
