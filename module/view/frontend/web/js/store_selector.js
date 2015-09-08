define([
    'jquery',
    'mage/template',
    'text!./templates/result.html',
    'ko',
    'Magento_Ui/js/modal/modal',
    'jquery/ui'
], function ($, mageTemplate, resultTmpl, ko, modal){
    "use strict";

    $.widget('magentoeseInStorePickup.storeSelector', {
        options: {
            resultContainer: "[data-role='result']",
            fieldset: "[data-role='store-search']",
            searchField: "[data-role='store-search-field']",
            url: '',
            selectionUrl: '',
            template: resultTmpl,
            storeSelectorVisible: false,
            storeSelectorSelector: "#instorepickup-storeselector",
            storeNavSelector: ".store-nav",
            modalWindow: null
        },

        /**
         * Creates widget 'magentoeseInStorePickup.storeSelector'
         * @private
         */
        _create: function () {
            this._on({'click [data-role="store-selector"]': $.proxy(this._onSearch, this)});
            this._on({'click [data-role="store-selector-choice"]': $.proxy(this._onStoreChoice, this)});

            $(this.options.storeNavSelector).on({'click': $.proxy(this._onNavClick, this)});
            this.createPopUp($(this.options.storeSelectorSelector));
        },

        /**
         * Respond to Navigation click event
         */
        _onNavClick: function() {
            if (this.options.modalWindow) {
                $(this.options.modalWindow).modal('openModal');
            } else {
                alert($t('Store Selector is disabled.'));
            }
        },

        /**
         * close the popup
         */
        _closePopup: function() {
            if (this.options.modalWindow) {
                $(this.options.modalWindow).modal('closeModal');
            } else {
                alert($t('Store Selector is disabled.'));
            }
        },

        /** Create popUp window for provided element */
        createPopUp: function(element) {
            this.options.modalWindow = element;
            var options = {
                'type': 'popup',
                'title': 'Find your local store',
                'modalClass': 'instorepickup-storeselector-popup',
                'responsive': true,
                'innerScroll': true,
                'buttons': []
            };
            modal(options, $(this.options.modalWindow));
        },

        /**
         * Get search results
         */
        _onSearch: function() {
            var self = this;

            $('body').find(self.options.messagesSelector).empty();
            self.element.find(self.options.resultContainer).empty();
            var params = {
                "searchCriteria": $(self.options.searchField).val()
            };

            $.ajax({
                url: self.options.url,
                dataType: 'json',
                data: params,
                context: $('body'),
                showLoader: true
            }).done(function (response) {
                self._drawResultTable(response);
            }).fail(function (response) {
                //var msg = $("<div/>").addClass("message notice").text(response.responseJSON.message);
                var msg = $("<div/>").addClass("message notice").text(response);
                this.find(self.options.resultContainer).prepend(msg);
            });
        },

        /**
         * Display results
         * @param response
         * @private
         */
        _drawResultTable: function(response){
            var tmpl = mageTemplate(this.options.template);
            tmpl = tmpl({data: response});
            this.element.find(this.options.resultContainer).append($(tmpl));
        },

        /**
         * Submit store selection
         */
        _onStoreChoice: function() {
            var self = this;

            var params = {
                "store-id": 255
            };

            $.ajax({
                url: self.options.selectionUrl,
                dataType: 'json',
                data: params,
                context: $('body'),
                showLoader: true
            }).done(function (response) {
                self._finishStoreChoice(response);
            }).fail(function (response) {
                //var msg = $("<div/>").addClass("message notice").text(response.responseJSON.message);
                var msg = $("<div/>").addClass("message notice").text(response);
                this.find(self.options.resultContainer).prepend(msg);
            });
        },

        /**
         * Finish processing store choice
         * @param response
         * @private
         */
        _finishStoreChoice: function(response){
            this._closePopup();
        }
    });

    return {
        'magentoeseInStorePickupStoreSelector': $.magentoeseInStorePickup.storeSelector
    };
});
