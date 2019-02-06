<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Api;

/**
 * Class WysiwygContentInterface
 *
 * @package     Magenerds\WysiwygWidget\Api
 * @file        WysiwygContentInterface.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
interface WysiwygContentInterface
{
    /**
     * Return the wysiwyg fields that should be encoded
     *
     * @return array
     */
    public static function getWysiwygContentFields(): array;
}
