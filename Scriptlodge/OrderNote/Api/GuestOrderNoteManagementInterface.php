<?php
/**
 * Copyright © Scriptlodge. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Scriptlodge\OrderNote\Api;

use Scriptlodge\OrderNote\Api\Data\OrderNoteInterface;

/**
 * Interface for saving the checkout order note
 * to the quote for guest users
 * @api
 */
interface GuestOrderNoteManagementInterface
{
    /**
     * @param string $cartId
     * @param OrderNoteInterface $orderNote
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     */
    public function saveOrderNote(
        $cartId,
        OrderNoteInterface $orderNote
    );
}
