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
use Magenerds\WysiwygWidget\Api\WysiwygContentInterface;
use Magenerds\WysiwygWidget\Wysiwyg\Encoder;
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
     * @var Encoder
     */
    protected $encoder;

    /**
     * WidgetPlugin constructor.
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
     * Encode values that cannot be saved in normal state because of quotes in them etc.
     * @param Subject $subject
     * @param Closure $proceed
     * @param $type
     * @param array $params
     * @param bool $asIs
     * @return mixed
     * @see Subject::getWidgetDeclaration()
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
        // check if widget should be encoded
        /** @var WysiwygContentInterface $type */
        if (class_exists($type) && is_subclass_of($type, WysiwygContentInterface::class)) {
            // iterate over values
            foreach ($params as $name => &$value) {
                // check if value is a string
                if ($value && is_string($value) && in_array($name, $type::getWysiwygContentFields())) {
                    $value = $this->encoder->encode($value);
                }
            }
        }

        // proceed with function
        return $proceed($type, $params, $asIs);
    }
}
