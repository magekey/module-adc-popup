/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'uiComponent',
    'jquery',
    'ko',
    'underscore',
    'MageKey_AdcPopup/js/model/popup',
    'Magento_Customer/js/customer-data',
    'mage/validation'
], function (Component, $, ko, _, cartPopup, customerData) {
    'use strict';

    return Component.extend({
        isLoading: ko.observable(false),
        items: cartPopup.items,
        productList: ko.observableArray([]),
        checkoutUrl: window.checkout && window.checkout.checkoutUrl
            ? window.checkout.checkoutUrl
            : null,

        /**
         * @override
         */
        initialize: function () {
            this._subscribeEvents();
            return this._super();
        },

        /** Init popup window */
        setModalElement: function (element) {
            if (cartPopup.modalWindow == null) {
                cartPopup.createPopUp(element);
            }
        },

        _subscribeEvents: function () {
            var self = this;
            var adcPopup = customerData.get('mgk_adcpopup');
            adcPopup.subscribe(function (data) {
                self._updateData(data);
            });
            self.items.subscribe(function (val) {
                if (val && val.length) {
                    cartPopup.showModal();
                } else {
                    cartPopup.closeModal();
                }
            });
        },

        _filterItems: function (itemsIds) {
            var self = this,
                cartData = customerData.get('cart')(),
                items = [];

            if (cartData.items && cartData.items.length) {
                _.each(cartData.items, function (value, key) {
                    if ($.inArray(value.item_id, itemsIds) >= 0) {
                        items.push(value);
                    }
                }, this);
            }
            return items;
        },

        _updateData: function (data) {
            if (data.items && data.items.length) {
                var items = this._filterItems(data.items);
                this.items(items);
                this.productList(data.product_list);
            }
        },

        isMultiple: function () {
            return this.items().length > 1;
        }
    });
});
