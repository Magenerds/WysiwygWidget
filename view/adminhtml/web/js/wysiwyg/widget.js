/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * Widget Element
 *
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Simon Sippert <s.sippert@techdivision.com>
 */
define([
    'jquery',
    'uiClass',
    'mage/adminhtml/wysiwyg/widget'
], function ($, Class) {
    // preserve original function
    // noinspection AmdModulesDependencies
    if (!widgetTools.openDialog_original) {
        // noinspection AmdModulesDependencies
        widgetTools.openDialog_original = widgetTools.openDialog;
    }

    // override widget dialog opener, magento doesn't allow opening 2+ instances per default
    // noinspection AmdModulesDependencies
    widgetTools.openDialog = function () {
        // fake dialog to be not open yet
        this.dialogOpened = false;

        // open another instance
        this.openDialog_original.apply(this, arguments);

        // set close function to only close current window
        // noinspection JSUnusedGlobalSymbols
        this.dialogWindow = {
            modal: function () {
                $('.modals-wrapper .modal-slide._show .action-close').last().trigger('click');
            }
        };
    };

    // preserve original function
    // noinspection JSUnresolvedVariable
    if (!$.mage.mediabrowser.prototype.getTargetElement_original) {
        // noinspection JSUnresolvedVariable
        $.mage.mediabrowser.prototype.getTargetElement_original = $.mage.mediabrowser.prototype.getTargetElement;
    }

    // fix target element to only set the image url and not the whole widget directive
    // noinspection JSUnresolvedVariable
    $.mage.mediabrowser.prototype.getTargetElement = function () {
        // get element
        let element = this.getTargetElement_original();

        // check if the target element is a widget image chooser field
        if (element.attr('name') === 'parameters[image]') {
            // add onChange event to retrieve the real image path
            element.change(function () {
                // split URL in pieces
                let split = element.val().split('/'),
                    index = $.inArray('___directive', split);

                // search for widget directive
                if (index > -1) {
                    // decode its value
                    // noinspection AmdModulesDependencies,RegExpRedundantEscape
                    let code = Base64.mageDecode(decodeURIComponent(split[index + 1])),
                        link = code.replace(/^\{\{media url="(.*?)"\}\}$/, '$1');

                    // append the link
                    element.val(link);
                }
            });
        }

        // return the element
        return element;
    };

    return Class.extend({
        /**
         * Initialize the Widget object
         *
         * @returns {exports}
         */
        initialize: function () {
            // noinspection JSUnresolvedFunction
            this._super();
            this.initWidget.apply(this, arguments);
            return this;
        },

        /**
         * Initialize the magento widget chooser
         */
        initWidget: function () {
            // get params
            // noinspection JSUnresolvedFunction
            let params = Array.prototype.slice.call(arguments);

            // set global variable name
            let widgetInstance = params[0];

            // build widget
            // noinspection AmdModulesDependencies
            window[widgetInstance] = new (WysiwygWidget.Widget.bind.apply(WysiwygWidget.Widget, params));

            /**
             * Insert the widget into the parent element
             */
            window[widgetInstance].insertWidget = window[widgetInstance].insertWidget.wrap(function (proceed) {
                // get current form
                let form = $('#' + this.formEl);

                // define control name
                let wysiwygControlName = '.admin__control-wysiwig';

                // get all wysiwyg editors
                let wysiwyg = form.find(wysiwygControlName).find('textarea'),
                    async = false;

                // iterate over editors
                wysiwyg.each(function () {
                    let textarea = $(this),
                        button = textarea.parent(wysiwygControlName).find('.action-show-hide');
                    if (!textarea.is(':hidden')) {
                        async = true;
                        setTimeout(function () {
                            button.click();
                        }, 50);
                    }
                    button.click();
                });

                // execute original function
                if (async) {
                    setTimeout(function () {
                        proceed();
                    }, 100);
                } else {
                    proceed();
                }
            });

            /**
             * Insert the widget into the parent element
             */
            window[widgetInstance].validateField = window[widgetInstance].validateField.wrap(function (proceed) {
                proceed();
                $('[id="insert_button"]').removeClass('disabled');
            });
        }
    });
});
