define(
    [
        'jquery',
        'uiComponent',
		'knockout'
    ],
    function ($, Component, ko) {
        'use strict';

		/**
		 * @param {Function} target
		 * @param {String} maxLength
		 * @return {*}
		 */
        ko.extenders.maxNoteLength = function (target, maxLength) {
            var timer;

            var result = ko.computed({
                read: target,
                write: function (val) {
                    if (maxLength > 0) {
                        clearTimeout(timer);
                        if (val.length > maxLength) {
                            var limitedVal = val.substring(0, maxLength);
                            if (target() === limitedVal) {
                                target.notifySubscribers();
                            } else {
                                target(limitedVal);
                            }
                            result.css("_error");
                            timer = setTimeout(function () { result.css(""); }, 800);
                        } else {
                            target(val);
                            result.css("");
                        }
                    } else {
                        target(val);
                    }
                }
            }).extend({ notify: 'always' });

            result.css = ko.observable();
            result(target());

            return result;
        };


        return Component.extend({
            defaults: {
                template: 'Scriptlodge_OrderNote/checkout/order-note-block'
            },

            initialize: function() {
                this._super();
                var self = this;
				this.note = ko.observable("").extend(
					{
						maxNoteLength: this.getMaxNoteLength()
					}
				);
                this.remainingCharacters = ko.computed(function(){
                    return self.getMaxNoteLength() - self.note().length;
                });
            },

            /**
             * Is order note has max length
             *
             * @return {Boolean}
             */
            hasMaxNoteLength: function() {
               return window.checkoutConfig.max_length > 0;
            },

            /**
             * Retrieve order note length limit
             *
             * @return {String}
             */
            getMaxNoteLength: function () {
              return window.checkoutConfig.max_length;
            },

            /**
             * Show/hide Order note Text Field based on condition
             *
             * @return {String}
             */
            isShowOrderNoteTextField: function () {
                return window.checkoutConfig.show_order_note > 0;
            }
        });
    }
);
