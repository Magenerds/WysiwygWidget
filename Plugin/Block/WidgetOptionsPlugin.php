<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Plugin\Block;

use Magenerds\WysiwygWidget\Api\Constants;
use Magento\Widget\Block\Adminhtml\Widget\Options as Subject;

/**
 * Class WidgetOptionsPlugin
 *
 * @package     Magenerds\WysiwygWidget\Plugin\Block
 * @file        WidgetOptionsPlugin.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
final class WidgetOptionsPlugin
{
    /**
     * Decode Base64-encoded fields to be output in their normal state
     * @see Subject::addFields()
     *
     * @param Subject $subject
     */
    public function beforeAddFields(
        Subject $subject
    )
    {
        // get widget values
        /** @noinspection PhpUndefinedMethodInspection */
        $params = $subject->getWidgetValues();

        // iterate over values
        foreach ($params as &$value) {
            // check if value has been encoded with base64
            if ($value && is_string($value) && strpos($value, Constants::BASE64_PREFIX) === 0) {
                // decode value
                $value = base64_decode(str_replace(Constants::BASE64_PREFIX, '', $value));
            }
        }

        // set decoded values
        /** @noinspection PhpUndefinedMethodInspection */
        $subject->setWidgetValues($params);
    }
}
