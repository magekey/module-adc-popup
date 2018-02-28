<?php
/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageKey\AdcPopup\Model\Config\Source;

use Magento\Catalog\Helper\Category as CategoryHelper;

class CategoryList implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var CategoryHelper
     */
    protected $categoryHelper;

    /**
     * @param CategoryHelper $categoryHelper
     */
    public function __construct(
        CategoryHelper $categoryHelper
    ) {
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $categories = $this->categoryHelper->getStoreCategories(false, false);
        $options = $this->convertToArray($categories);
        return $options;
    }

    /**
     * Convert categories to array
     *
     * @param \Magento\Framework\Data\Tree\Node\Collection $categories
     * @param int $offset
     * @return array
     */
    protected function convertToArray(
        \Magento\Framework\Data\Tree\Node\Collection $categories,
        $offset = 0
    ) {
        $options = [];
        foreach ($categories as $category) {
            $options[] = [
                'value' => $category->getId(),
                'label' => html_entity_decode(str_repeat('&nbsp;', $offset * 5), ENT_COMPAT, 'UTF-8')
                    . $category->getName()
            ];
            if ($category->hasChildren()) {
                $options = array_merge($options, $this->convertToArray($category->getChildren(), $offset + 1));
            }
        }
        return $options;
    }
}
