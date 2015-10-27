/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'uiComponent',
        'Magento_Checkout/js/model/quote'
    ],
    function($, Component, quote) {
        'use strict';
        var quoteData = window.checkoutConfig.quoteData;
        return Component.extend({
            defaults: {
                template: 'MagentoEse_InStorePickup/instorepickup'
            },
            quoteData: quoteData,

            isVisible: function() {
                return !!Number(quoteData.has_instorepickup_fulfillment);
            }
        });
    }
);
