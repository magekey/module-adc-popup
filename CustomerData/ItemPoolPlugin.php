<?php
/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageKey\AdcPopup\CustomerData;

use Magento\Quote\Model\Quote\Item;
use MageKey\AdcPopup\Model\Storage as Storage;

class ItemPoolPlugin
{
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param Storage $storage
     */
    public function __construct(
        \Magento\Catalog\Helper\Image $imageHelper,
        Storage $storage
    ) {
        $this->imageHelper = $imageHelper;
        $this->storage = $storage;
    }

    /**
     * @return \Magento\Checkout\CustomerData\ItemPoolInterface $subject
     * @return callable $proceed
     * @return Item $item
     */
    public function aroundGetItemData(
        \Magento\Checkout\CustomerData\ItemPoolInterface $subject,
        callable $proceed,
        Item $item
    ) {
        $return = $proceed($item);

        $items = $this->storage->getItems();
        $imageView = count($items) > 1 ? 'mgk_adcpopup_cart_multi' : 'mgk_adcpopup_cart_single';
        $imageHelper = $this->imageHelper->init($item->getProduct(), $imageView);
        $return['mgk_adcpopup_image'] = [
            'src' => $imageHelper->getUrl(),
            'alt' => $imageHelper->getLabel(),
            'width' => $imageHelper->getWidth(),
            'height' => $imageHelper->getHeight(),
        ];

        return $return;
    }
}
