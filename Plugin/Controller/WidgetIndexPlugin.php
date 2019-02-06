<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Plugin\Controller;

use Magenerds\WysiwygWidget\Block\Widget\Editor;
use Magento\Widget\Controller\Adminhtml\Widget\Index as Subject;
use Magento\Widget\Model\Widget\Config;

/**
 * Class WidgetIndexPlugin
 *
 * @package     Magenerds\WysiwygWidget\Plugin\Controller
 * @file        WidgetIndexPlugin.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
final class WidgetIndexPlugin
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * WidgetIndexPlugin constructor.
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    )
    {
        $this->config = $config;
    }

    /**
     * Hide wysiwyg editor widget in stacked widget list
     * @see Subject::execute()
     *
     * @param Subject $subject
     */
    public function beforeExecute(Subject $subject)
    {
        // if we're inserting/editing a widget IN a wysiwyg widget, we won't allow to insert a new wysiwyg editor
        if (strpos($subject->getRequest()->getParam('widget_target_id'), 'options_fieldset') === 0) {
            // get skipped widgets and decode them
            $skipWidgets = $subject->getRequest()->getParam('skip_widgets');
            if ($skipWidgets) {
                $skipWidgets = $this->config->decodeWidgetsFromQuery($skipWidgets) ?: [];
            } else {
                $skipWidgets = [];
            }

            // add editor as skipped widget
            $skipWidgets[] = Editor::class;

            // set to request parameters
            $params = $subject->getRequest()->getParams();
            $params['skip_widgets'] = $this->config->encodeWidgetsToQuery($skipWidgets);
            $subject->getRequest()->setParams($params);
        }
    }
}
