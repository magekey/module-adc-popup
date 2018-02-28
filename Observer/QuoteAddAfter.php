<?php
/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageKey\AdcPopup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use MageKey\AdcPopup\Helper\Data as DataHelper;

class QuoteAddAfter implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * @return void
     */
    public function execute(Observer $observer)
    {
        $items = $observer->getItems();
        $this->registry->register(DataHelper::REGISTER_ITEMS_KEY, $items);
    }
}
