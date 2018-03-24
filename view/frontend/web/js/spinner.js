/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'jquery',
    'jquery/ui',
    'MageKey_AdcPopup/js/model/popup'
], function ($, ui, cartPopup) {
    "use strict";

    $.widget('mage.mgkCartSpinner', $.ui.spinner, {

        options: {
            updateItemQtyUrl: window.checkout && window.checkout.updateItemQtyUrl
                ? window.checkout.updateItemQtyUrl
                : null,
            incremental: false,
            min: 1
        },

        _value: function ( value, allowAny ) {
            var oldValue = this.element.val();
            this._super(value, allowAny);
            if (oldValue != value) {
                cartPopup.messages.clear();
                this._updateItemQty(oldValue, value);
            }
        },

        _buttonHtml: function () {
            return "" +
                "<a class='ui-spinner-button ui-spinner-up ui-corner-tr'>" +
                    "<span class='ui-icon " + this.options.icons.up + "'>&#43;</span>" +
                "</a>" +
                "<a class='ui-spinner-button ui-spinner-down ui-corner-br'>" +
                    "<span class='ui-icon " + this.options.icons.down + "'>&#8722;</span>" +
                "</a>";
        },

        _updateItemQty: function (oldValue, newValue) {
            var self = this;
            if (self.options.updateItemQtyUrl) {
                $.ajax({
                    url: self.options.updateItemQtyUrl,
                    type: 'post',
                    dataType: 'json',
                    showLoader: true,
                    data: {
                        item_id: this.options.item_id,
                        item_qty: newValue,
                        form_key: $.mage.cookies.get('form_key')
                    },
                    success: function (response) {
                        if (response.error_message) {
                            self.element.val(oldValue);
                            cartPopup.messages.addErrorMessage({message: response.error_message});
                        }
                    }
                });
            }
        }
    });

    return $.mage.mgkCartSpinner;
});
