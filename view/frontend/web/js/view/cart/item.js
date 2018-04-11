/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'uiComponent',
    'jquery',
    'MageKey_AdcPopup/js/model/popup',
    'Magento_Ui/js/modal/confirm'
], function (Component, $, cartPopup, confirm) {
    'use strict';

    return Component.extend({
    	messages: cartPopup.messages,
    	updateItemQtyUrl: window.checkout && window.checkout.updateItemQtyUrl
            ? window.checkout.updateItemQtyUrl
            : null,
        removeItemUrl: window.checkout && window.checkout.removeItemUrl
	       	? window.checkout.removeItemUrl
	     	: null,

	   	updateItemQty: function (item, newValue, callback) {
            cartPopup.messages.clear();
            if (this.updateItemQtyUrl) {
                $.ajax({
                    url: this.updateItemQtyUrl,
                    type: 'post',
                    dataType: 'json',
                    showLoader: true,
                    data: {
                        item_id: item.item_id,
                        item_qty: newValue,
                        form_key: $.mage.cookies.get('form_key')
                    },
                    success: function (response) {
                        if (response.error_message) {
                            cartPopup.messages.addErrorMessage({message: response.error_message});
                        }
                        if (callback) {
                        	callback(response);
                        }
                    }
                });
            }
        },

        removeItem: function (item) {
            var self = this;
            if (self.removeItemUrl && item.item_id) {
                confirm({
                    content: $.mage.__('Are you sure you would like to remove this item from the shopping cart?'),
                    actions: {
                        confirm: function () {
                            $.ajax({
                                url: self.removeItemUrl,
                                type: 'post',
                                dataType: 'json',
                                showLoader: true,
                                data: {
                                    item_id: item.item_id,
                                    form_key: $.mage.cookies.get('form_key')
                                },
                                success: function (response) {
                                    if (response.error_message) {
                                        cartPopup.messages.addErrorMessage({message: response.error_message});
                                    } else {
                                    	cartPopup.removeItemById(item.item_id);
                                    }
                                }
                            });
                        }
                    }
                });
            }
            return false;
        }
    });
});