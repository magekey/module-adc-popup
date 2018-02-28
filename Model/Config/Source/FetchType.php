<?php
/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageKey\AdcPopup\Model\Config\Source;

class FetchType implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'current_categories', 'label' => __('Current Categories')],
            ['value' => 'specific_categories', 'label' => __('Specific Categories')],
        ];
    }
}
