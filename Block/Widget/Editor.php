<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\WysiwygWidget\Block\Widget;

use Exception;
use Magenerds\WysiwygWidget\Api\WysiwygContentInterface;
use Magenerds\WysiwygWidget\Wysiwyg\Encoder;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

/**
 * Class Editor
 *
 * @package     Magenerds\WysiwygWidget\Block\Widget
 * @file        Editor.php
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
class Editor extends Template implements BlockInterface, WysiwygContentInterface
{
    /**
     * @var Encoder
     */
    protected $encoder;

    /**
     * Return the wysiwyg fields that should be encoded
     *
     * @return array
     */
    public static function getWysiwygContentFields(): array
    {
        return ['content'];
    }

    /**
     * Editor constructor.
     *
     * @param Context $context
     * @param Encoder $encoder
     * @param array $data
     */
    public function __construct(
        Context $context,
        Encoder $encoder,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->encoder = $encoder;
    }

    /**
     * Prepare HTML content
     *
     * @return string
     * @throws Exception
     */
    protected function _toHtml()
    {
        return $this->encoder->decodeAndFilter($this->getData('content'));
    }
}
