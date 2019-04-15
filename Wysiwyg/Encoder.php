<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Wysiwyg;

use Exception;
use Magento\Cms\Model\Template\FilterProvider;

/**
 * Class Encoder
 *
 * @package     Magenerds\WysiwygWidget\Wysiwyg
 * @file        Encoder.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
class Encoder
{
    /**
     * This prefix gets added to the encoded content
     *
     * @type string
     */
    const ENCODER_PREFIX = '---BASE64---';

    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * Encoder constructor.
     *
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        FilterProvider $filterProvider
    )
    {
        $this->filterProvider = $filterProvider;
    }

    /**
     * Check if value is encoded
     *
     * @param string $value
     * @return bool
     */
    public function isEncoded($value)
    {
        return $value && is_string($value) && strpos($value, static::ENCODER_PREFIX) === 0;
    }

    /**
     * Encode value
     *
     * @param string $value
     * @return string
     */
    public function encode($value)
    {
        return static::ENCODER_PREFIX . base64_encode($value);
    }

    /**
     * Decode value
     *
     * @param string $value
     * @return string
     */
    public function decode($value)
    {
        if ($this->isEncoded($value)) {
            return base64_decode(str_replace([static::ENCODER_PREFIX, ' '], ['', '+'], $value));
        }
        return $value;
    }

    /**
     * Decode and filter value
     *
     * @param string $value
     * @return string
     * @throws Exception
     */
    public function decodeAndFilter($value)
    {
        return $this->filterProvider->getPageFilter()->filter($this->decode($value));
    }
}
