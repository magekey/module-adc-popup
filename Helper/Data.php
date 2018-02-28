<?php
/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageKey\AdcPopup\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Registry key
     */
    const REGISTER_ITEMS_KEY = 'mgk_adcpopup_items';

    /**
     * General
     */
    const XML_PATH_GENERAL_ENABLED = 'mgk_adcpopup/general/enabled';

    /**
     * Product List
     */
    const XML_PATH_PRODUCTLIST_ENABLED = 'mgk_adcpopup/product_list/enabled';

    const XML_PATH_PRODUCTLIST_FETCH_TYPE = 'mgk_adcpopup/product_list/fetch_type';

    const XML_PATH_PRODUCTLIST_SPECIFIC_CATEGORIES = 'mgk_adcpopup/product_list/specific_categories';

    const XML_PATH_PRODUCTLIST_PRODUCT_COUNT = 'mgk_adcpopup/product_list/product_count';

    /**
     * Default configs
     */
    const DEFAULT_PRODUCT_LIST_COUNT = 4;

    /**
     * Check if module enabled
     *
     * @param mixed $scopeCode
     * @return bool
     */
    public function isEnabled($scopeCode = null)
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_GENERAL_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    /**
     * Check if product list enabled
     *
     * @param mixed $scopeCode
     * @return bool
     */
    public function isProductListEnabled($scopeCode = null)
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_PRODUCTLIST_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    /**
     * Retrieve product list fetch type
     *
     * @param mixed $scopeCode
     * @return string
     */
    public function getProductListFetchType($scopeCode = null)
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_PRODUCTLIST_FETCH_TYPE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    /**
     * Retrieve product list specific categories
     *
     * @param mixed $scopeCode
     * @return array
     */
    public function getProductListSpecificCategories($scopeCode = null)
    {
        $value = $this->scopeConfig->getValue(
            static::XML_PATH_PRODUCTLIST_SPECIFIC_CATEGORIES,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
        return explode(',', (string)$value);
    }

    /**
     * Retrieve product list count
     *
     * @param mixed $scopeCode
     * @return array
     */
    public function getProductListProductCount($scopeCode = null)
    {
        $value = (int)$this->scopeConfig->getValue(
            static::XML_PATH_PRODUCTLIST_PRODUCT_COUNT,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
        return $value ?: static::DEFAULT_PRODUCT_LIST_COUNT;
    }
}
