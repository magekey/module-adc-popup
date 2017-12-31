<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\AdcPopup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\Data\CartItemInterface;

use MageKey\AdcPopup\Model\Storage as Storage;

class CartUpdated implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Cart
     */
    private $cart;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @param \Magento\Checkout\Model\Cart $cart
     * @param Storage $storage
     */
    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        Storage $storage
    ) {
        $this->cart = $cart;
        $this->storage = $storage;
    }

    /**
     * @return void
     */
    public function execute(Observer $observer)
    {
        $quote = $this->cart->getQuote();
        $quoteItems = $quote->getAllVisibleItems();
        $quoteItem = end($quoteItems);
        $this->_updateStorage($quoteItem);
    }

    /**
     * Update storage
     *
     * @param CartItemInterface $cartItem
     * @return void
     */
    protected function _updateStorage(CartItemInterface $cartItem)
    {
        $items = $this->storage->getItems();
        if ($cartItemId = $cartItem->getId()) {
            $items[] = $cartItemId;
            $this->storage->setItems(array_unique($items));
        }
    }
}
