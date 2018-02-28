<?php
/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageKey\AdcPopup\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

use MageKey\AdcPopup\Model\Storage as Storage;
use MageKey\AdcPopup\Helper\Data as DataHelper;

/**
 * Popup source
 */
class Popup extends \Magento\Framework\DataObject implements SectionSourceInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $checkoutCart;

    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $productVisibility;

    /**
     * @var \Magento\Checkout\Helper\Data
     */
    protected $checkoutHelper;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var DataHelper
     */
    protected $dataHelper;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var \Magento\Quote\Model\Quote|null
     */
    protected $quote = null;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Checkout\Model\Cart $checkoutCart
     * @param ProductCollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Checkout\Helper\Data $checkoutHelper
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param DataHelper $dataHelper
     * @param Storage $storage
     * @param array $data
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\Cart $checkoutCart,
        ProductCollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Magento\Catalog\Helper\Image $imageHelper,
        DataHelper $dataHelper,
        Storage $storage,
        array $data = []
    ) {
        parent::__construct($data);
        $this->checkoutSession = $checkoutSession;
        $this->checkoutCart = $checkoutCart;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productVisibility = $productVisibility;
        $this->checkoutHelper = $checkoutHelper;
        $this->imageHelper = $imageHelper;
        $this->dataHelper = $dataHelper;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        $data = [];
        if (!$this->dataHelper->isEnabled()) {
            return $data;
        }

        $items = $this->getAddedItems();
        if (!empty($items)) {
            $data = [
                'items' => $items,
                'product_list' => $this->getProductList($items),
                'summary' => $this->getSummary(),
            ];
        }
        return $data;
    }

    /**
     * Get array of added items
     *
     * @return int[]
     */
    protected function getAddedItems()
    {
        $items = $this->storage->getItems();
        if (!$this->checkoutCart->getSummaryQty()) {
            $items = [];
        }
        return $items;
    }

    /**
     * Get product list
     *
     * @param array $affectedItems
     * @return array
     */
    protected function getProductList(array $affectedItems)
    {
        $productList = [];
        if (!$this->dataHelper->isProductListEnabled()) {
            return $productList;
        }

        $affectedProductIds = [];
        $categoryIds = [];
        $fetchType = $this->dataHelper->getProductListFetchType();
        switch ($fetchType) {
            case 'current_categories':
                $quote = $this->getQuote();
                foreach ($quote->getItems() as $item) {
                    if (in_array($item->getId(), $affectedItems)) {
                        $product = $item->getProduct();
                        $affectedProductIds[] = $product->getId();
                        $categoryIds = array_merge($categoryIds, $product->getCategoryIds());
                    }
                }
                break;
            case 'specific_categories':
                $categoryIds = $this->dataHelper->getProductListSpecificCategories();
                break;
        }

        if (!empty($categoryIds)) {
            $productCollection = $this->getProductCollection($categoryIds, $affectedProductIds);
            $productCollection->load();
            $productList = $this->getProductListData($productCollection);
        }

        return $productList;
    }

    /**
     * Get summary items
     *
     * @return array
     */
    public function getSummary()
    {
        $summaryItems = [];

        $qty = (int)$this->checkoutCart->getSummaryQty();
        $totals = $this->getQuote()->getTotals();

        if (isset($totals['subtotal'])) {
            $value = $totals['subtotal']->getValue();
            $summaryItems[] = [
                'label' => $qty . ' ' . __('product%1', $qty > 1 ? 's' : ''),
                'value' => $this->checkoutHelper->formatPrice($totals['subtotal']->getValue()),
            ];
        }

        if (isset($totals['shipping'])) {
            $value = $totals['shipping']->getValue();
            $summaryItems[] = [
                'label' => $totals['shipping']->getTitle(),
                'value' => __($value ? $this->checkoutHelper->formatPrice($value) : 'Free'),
            ];
        }

        return $summaryItems;
    }

    /**
     * Get active quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        if (null === $this->quote) {
            $this->quote = $this->checkoutSession->getQuote();
        }
        return $this->quote;
    }

    /**
     * Get product collection
     *
     * @param array $categoryIds
     * @param array $affectedProductIds
     * @return ProductCollection
     */
    protected function getProductCollection(array $categoryIds = [], array $affectedProductIds = [])
    {
        $productCollection = $this->productCollectionFactory
            ->create()
            ->addAttributeToSelect('*')
            ->addCategoriesFilter(['in' => $categoryIds])
            ->addFinalPrice()
            ->setVisibility($this->productVisibility->getVisibleInSiteIds());
        if (!empty($affectedProductIds)) {
            $productCollection->addFieldToFilter('entity_id', ['nin' => $affectedProductIds]);
        }
        if ($productCount = $this->dataHelper->getProductListProductCount()) {
            $productCollection->setPageSize($productCount);
        }
        $productCollection->getSelect()->orderRand();
        $productCollection->addOptionsToResult();
        return $productCollection;
    }

    /**
     * Get product list data
     *
     * @param ProductCollection $productCollection
     * @return array
     */
    protected function getProductListData(ProductCollection $productCollection)
    {
        $productList = [];

        foreach ($productCollection as $product) {
            $imageHelper = $this->imageHelper->init($product, 'mgk_adcpopup_product_list');
            $productList[] = [
                'product_id' => $product->getId(),
                'product_name' => $product->getName(),
                'product_sku' => $product->getSku(),
                'product_url' => $product->getUrlModel()->getUrl($product),
                'product_price' => $this->checkoutHelper->formatPrice($product->getFinalPrice()),
                'product_price_value' => $product->getFinalPrice(),
                'product_image' => [
                    'src' => $imageHelper->getUrl(),
                    'alt' => $imageHelper->getLabel(),
                    'width' => $imageHelper->getWidth(),
                    'height' => $imageHelper->getHeight(),
                ],
            ];
        }
        return $productList;
    }
}
