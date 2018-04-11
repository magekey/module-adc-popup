/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'jquery',
    'jquery/ui'
], function ($) {
    "use strict";

    $.widget('mage.mgkCartSpinner', $.ui.spinner, {

        options: {
            incremental: false,
            min: 1,
            updateAction: null
        },

        _value: function ( value, allowAny ) {
            var self = this;
            var oldValue = this.element.val();
            this._super(value, allowAny);
            if (self.options.updateAction && oldValue != value) {
                self.options.updateAction(value, function (response) {
                    if (response.error_message) {
                        self.element.val(oldValue);
                    }
                });
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
        }
    });

    return $.mage.mgkCartSpinner;
});
