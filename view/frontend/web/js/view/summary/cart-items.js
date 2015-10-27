/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'Magento_Checkout/js/model/totals',
        'uiComponent',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Checkout/js/model/quote'
    ],
    function (ko, totals, Component, stepNavigator, quote) {
        'use strict';
        var quoteData = window.checkoutConfig.quoteData;
        var instorepickupMethod = 'pick-up';
        return Component.extend({
            defaults: {
                template: 'MagentoEse_InStorePickup/summary/cart-items'
            },
            totals: totals.totals(),
            getItems: quote.getItems(),
            quoteData: quoteData,
            quoteItemData: quote.getItems(),
            getItemsQty: function () {
                return parseFloat(this.totals.items_qty);
            },
            isItemsBlockExpanded: function () {
                return quote.isVirtual() || stepNavigator.isProcessed('shipping');
            },
            hasInstorepickupFulfillment: function () {
                return !!Number(quoteData.has_instorepickup_fulfillment);
            },
            getInstorepickupItemsQty: function () {
                var count = 0;
                _.each(quote.getItems(), function (value) {
                    if (value.instorepickup_addtocart_method) {
                        if (value.instorepickup_addtocart_method == instorepickupMethod) {
                            count += parseFloat(value.qty);
                        }
                    }
                });
                return count;
            },
            getNonInstorepickupItemsQty: function () {
                var count = 0;
                _.each(quote.getItems(), function (value) {
                    if (value.instorepickup_addtocart_method) {
                        if (value.instorepickup_addtocart_method != instorepickupMethod) {
                            count += parseFloat(value.qty);
                        }
                    } else {
                        count += parseFloat(value.qty);
                    }
                });
                return count;
            },
            getInstorepickupItems: function () {
                var items = []
                _.each(quote.getItems(), function (value) {
                    if (value.instorepickup_addtocart_method) {
                        if (value.instorepickup_addtocart_method == instorepickupMethod) {
                            items.push(value);
                        }
                    }
                });
                return items;
            },
            getNonInstorepickupItems: function () {
                var items = []
                _.each(quote.getItems(), function (value) {
                    if (value.instorepickup_addtocart_method) {
                        if (value.instorepickup_addtocart_method != instorepickupMethod) {
                            items.push(value);
                        }
                    } else {
                        items.push(value);
                    }
                });
                return items;
            }
        });
    }
);
