<?php
/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageKey\AdcPopup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\Data\CartItemInterface;

use MageKey\AdcPopup\Model\Storage as Storage;
use MageKey\AdcPopup\Helper\Data as DataHelper;

class CartAddComplete implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @param \Magento\Framework\Registry $registry
     * @param Storage $storage
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        Storage $storage
    ) {
        $this->registry = $registry;
        $this->storage = $storage;
    }

    /**
     * @return void
     */
    public function execute(Observer $observer)
    {
        $items = $this->registry->registry(DataHelper::REGISTER_ITEMS_KEY);
        if (is_array($items)) {
            $storageItems = [];
            foreach ($items as $item) {
                if (!$item->getParentItemId()) {
                    $storageItems[] = $item->getId();
                }
            }
            if (!empty($storageItems)) {
                $this->storage->setItems($storageItems);
            }
        }
    }
}
