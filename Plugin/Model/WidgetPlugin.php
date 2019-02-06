<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Plugin\Model;

use Closure;
use Magenerds\WysiwygWidget\Block\Widget\Editor;
use Magenerds\WysiwygWidget\Api\Constants;
use Magento\Widget\Model\Widget as Subject;

/**
 * Class WidgetPlugin
 *
 * @package     Magenerds\WysiwygWidget\Plugin\Model
 * @file        WidgetPlugin.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
final class WidgetPlugin
{
    /**
     * Encode values with Base64 that cannot be saved in normal state because of quotes in them etc.
     * @see Subject::getWidgetDeclaration()
     *
     * @param Subject $subject
     * @param Closure $proceed
     * @param $type
     * @param array $params
     * @param bool $asIs
     * @return mixed
     */
    public function aroundGetWidgetDeclaration(
        /** @noinspection PhpUnusedParameterInspection */
        Subject $subject, // NOSONAR
        Closure $proceed,
        $type,
        $params = [],
        $asIs = true
    )
    {
        // check for editor widget
        if ($type === Editor::class) {
            // iterate over values
            foreach ($params as $name => &$value) {
                // check if value is a string
                if ($value && is_string($value) && $name === 'content') {
                    // encode value
                    $value = Constants::BASE64_PREFIX . base64_encode($value);
                }
            }
        }

        // proceed with function
        return $proceed($type, $params, $asIs);
    }
}
