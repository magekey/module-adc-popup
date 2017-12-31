/**
 * Copyright © MageKey. All rights reserved.
 */
define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
],function ($, modal, $t) {
    'use strict';

    return {
        modalWindow: null,

        /** Create popup window */
        createPopUp: function (element) {
            this.modalWindow = element;
            var options = {
                'title': $t('Added to your shopping cart'),
                'type': 'popup',
                'modalClass': 'mgk-adc-popup',
                'responsive': true,
                'buttons': []
            };
            modal(options, $(this.modalWindow));
        },

        /** Show save cart popup window */
        showModal: function () {
            $(this.modalWindow).modal('openModal');
        },

        /** Close save cart popup window */
        closeModal: function () {
            $(this.modalWindow).modal('closeModal');
        }
    }
});
