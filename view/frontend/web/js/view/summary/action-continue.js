/**
 * Copyright © MageKey. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'uiComponent',
    'MageKey_AdcPopup/js/model/popup'
], function (Component, cartPopup) {
    'use strict';

    return Component.extend({

        clickAction: function () {
            cartPopup.closeModal();
        }
    });
});