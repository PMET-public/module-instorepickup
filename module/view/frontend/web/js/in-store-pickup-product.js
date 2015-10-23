define([
    'jquery'
], function ($){
    "use strict";
    $.widget('MagentoEse.inStorePickupProduct', {
        options: {},
        _init: function () {
            this.setOnStoreSwitch();
        },
        setOnStoreSwitch: function () {
            $('.addtocart-mode input:radio').on('change', function() {
                $('.addtocart-mode .control')
                    .removeClass("selected-radio")
                    .find('input[name=instorepickup_addtocart_method]:checked')
                    .parents('.control')
                    .addClass("selected-radio");
                if ($('.addtocart-mode input[name=instorepickup_addtocart_method]:checked').val() == "pick-up") {
                    $('.addtocart-mode .address').show();
                }
                else {
                    $('.addtocart-mode .address').hide();
                }
            });
        }
    });
    return $.MagentoEse.inStorePickupProduct;
});