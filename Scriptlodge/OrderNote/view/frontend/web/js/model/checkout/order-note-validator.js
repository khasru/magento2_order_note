define(
    [
        'jquery',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'mage/url',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Ui/js/model/messageList',
        'mage/translate'
    ],
	function ($, customer, quote, urlBuilder, urlFormatter, errorProcessor, messageContainer, __) {
        'use strict';

        return {

            /**
             * Make an ajax PUT request to save internal order note in the quote.
             *
             * @returns {Boolean}
             */
            validate: function () {
                if(this.isOrderNoteEnable()==0){
                    return true;
                }
                var isCustomer = customer.isLoggedIn();
                var form = $('.payment-method input[name="payment[method]"]:checked').parents('.payment-method').find('form.order-note-form');

                var quoteId = quote.getQuoteId();
                var url;

                // validate max length
                var order_note = form.find('.input-text.internal-order-note').val();
                if (this.hasMaxOrderNoteLength() && order_note.length > this.getMaxOrderNoteLength()) {
                    messageContainer.addErrorMessage({ message: __("The internal order note entered exceeded the limit") });
                    return false;
                }

                if (isCustomer) {
                    url = urlBuilder.createUrl('/carts/mine/setordernote', {})
                } else {
                    url = urlBuilder.createUrl('/guest-carts/:cartId/setordernote', {cartId: quoteId});
                }

                var payload = {
                    cartId: quoteId,
                    orderNote: {
                        note: order_note
                    }
                };

                if (!payload.orderNote.note) {
                    return true;
                }

                var result = true;

                $.ajax({
                    url: urlFormatter.build(url),
                    data: JSON.stringify(payload),
                    global: false,
                    contentType: 'application/json',
                    type: 'PUT',
                    async: false
                }).done(
                    function (response) {
                        result = true;
                    }
                ).fail(
                    function (response) {
                        result = false;
                        errorProcessor.process(response);
                    }
                );

                return result;
            },

            /**
             * Is order note has max length
             *
             * @return {Boolean}
             */
            hasMaxOrderNoteLength: function() {
                 return window.checkoutConfig.max_length > 0;
            },

            /**
             * Retrieve order note length limit
             *
             * @return {String}
             */
            getMaxOrderNoteLength: function () {
                 return window.checkoutConfig.max_length;
            },
            /**
             * Show/hide Order note Text Field based on condition
             *
             * @return {String}
             */
            isOrderNoteEnable: function () {
                return window.checkoutConfig.show_order_note > 0;
            }
        };
    }
);
