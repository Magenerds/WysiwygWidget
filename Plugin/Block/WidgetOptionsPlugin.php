<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Plugin\Block;

use Magenerds\WysiwygWidget\Wysiwyg\Encoder;
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
     * @var Encoder
     */
    protected $encoder;

    /**
     * WidgetOptionsPlugin constructor.
     *
     * @param Encoder $encoder
     */
    public function __construct(
        Encoder $encoder
    )
    {
        $this->encoder = $encoder;
    }

    /**
     * Decode encoded fields to be output in their normal state
     * @param Subject $subject
     * @see Subject::addFields()
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
            $value = $this->encoder->decode($value);
        }

        // set decoded values
        /** @noinspection PhpUndefinedMethodInspection */
        $subject->setWidgetValues($params);
    }
}
