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
            storeDetailUrl: '',
            selectedStore: '',
            template: resultTmpl,
            storeSelectorVisible: false,
            storeSelectorSelector: "#instorepickup-storeselector",
            storeNavSelector: ".store-nav",
            storeSearchInputSelector: "#store-search",
            modalWindow: null,
            storeSearchTriggerSelector: ".instorepickup-search-trigger",
            storeSwitcherSelector: "#switcher-instorepickup",
            navContainerSelector: ".instorepickup-nav-container",
            storeDetailSelector: ".switcher-dropdown"
        },

        /**
         * Creates widget 'magentoeseInStorePickup.storeSelector'
         * @private
         */
        _create: function () {
            var self = this;

            this._on({'click [data-role="store-selector"]': $.proxy(this._onSearch, this)});
            $(this.options.storeSearchInputSelector).keyup(function (e) {
                if (e.keyCode == 13) {
                    self._onSearch();
                }
            });

            $(this.options.storeSearchTriggerSelector).on({'click': $.proxy(this._onNavClick, this)});
            this.createPopUp($(this.options.storeSelectorSelector));
            $(this.options.storeSelectorSelector).show();
            $(this.options.storeSwitcherSelector).show();
        },

        /**
         * Respond to Navigation click event
         */
        _onNavClick: function() {
            if (this.options.modalWindow) {
                $(this.options.modalWindow).modal('openModal');
            } else {
                alert('Store Selector is disabled.');
            }
        },

        /**
         * close the popup
         */
        _closePopup: function() {
            if (this.options.modalWindow) {
                $(this.options.modalWindow).modal('closeModal');
            } else {
                alert('Store Selector is disabled.');
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
                var msg = $("<div/>").addClass("message notice").text(response.responseText);
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

            $('button[data-role="store-selector-choice"]').on('click', $.proxy(this._onStoreChoice, this));
        },

        /**
         * Submit store selection
         */
        _onStoreChoice: function(context) {
            var self = this;

            var params = {
                "store-id": context.currentTarget.parentElement.elements["store-id"].value
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
                var msg = $("<div/>").addClass("message notice").text(response.responseText);
                this.find(self.options.resultContainer).prepend(msg);
            });
        },

        /**
         * Finish processing store choice
         * @param response
         * @private
         */
        _finishStoreChoice: function(response){
            var self = this;

            // Display the chosen store
            if ($(this.options.navContainerSelector+' strong a.instorepickup-search-trigger').length > 0) {
                $(this.options.navContainerSelector+' strong a.instorepickup-search-trigger').remove();
                $(this.options.navContainerSelector+' strong').append('<span class="action toggle instorepickup-trigger" id="switcher-instorepickup-trigger" title="My Store">My Store: ' + response.chosenStore['name'] + '</span>')
            } else {
                $(this.options.navContainerSelector+' strong span.instorepickup-trigger').text('My Store: ' + response.chosenStore['name']);
            }
            // ajax call to get html for detail section
            $.ajax({
                url: this.options.storeDetailUrl,
                dataType: 'html',
                context: $('body')
            }).done(function (response) {
                $(self.options.storeDetailSelector).empty();
                $(self.options.storeDetailSelector).append(response);
            }).fail(function (response) {
                var msg = $("<div/>").addClass("message notice").text(response.responseText);
                this.find(self.options.resultContainer).prepend(msg);
            });

            this._closePopup();
        }
    });

    return {
        'magentoeseInStorePickupStoreSelector': $.magentoeseInStorePickup.storeSelector
    };
});
