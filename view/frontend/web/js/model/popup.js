/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'Magento_Ui/js/model/messageList'
],function ($, modal, $t, messageList) {
    'use strict';

    return {
        modalWindow: null,
        messages: messageList,

        /** Create popup window */
        createPopUp: function (element) {
            this.modalWindow = element;
            var options = {
                'title': $t('Added to your shopping cart'),
                'type': 'popup',
                'modalClass': 'mgk-adcpopup-modal',
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
