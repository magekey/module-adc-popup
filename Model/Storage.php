<?php
/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageKey\AdcPopup\Model;

class Storage
{
    /**
     * Keys
     */
    const SESS_PREFIX = 'mgk_adcpopup_';

    const SESS_KEY_ITEMS = 'items';

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Get data
     *
     * @param string $key
     * @return array
     */
    public function getData($key)
    {
        if (!isset($this->_data[$key])) {
            $this->_data[$key] = $this->checkoutSession->getData(static::SESS_PREFIX . $key, true);
        }
        return $this->_data[$key];
    }

    /**
     * Set data
     *
     * @param string $key
     * @param string $value
     * @return array
     */
    public function setData($key, $value)
    {
        $this->_data[$key] = $value;
        $this->checkoutSession->setData(static::SESS_PREFIX . $key, $value);
        return $this;
    }

    /**
     * Get items
     *
     * @return array
     */
    public function getItems()
    {
        $items = $this->getData(static::SESS_KEY_ITEMS);
        if (!is_array($items)) {
            $items = [];
        }
        return $items;
    }

    /**
     * Set items
     *
     * @param array $items
     * @return array
     */
    public function setItems(array $items = [])
    {
        $this->setData(static::SESS_KEY_ITEMS, $items);
        return $this;
    }
}
