<?php
/**
 * Copyright © Scriptlodge. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Scriptlodge\OrderNote\Api;

use Scriptlodge\OrderNote\Api\Data\OrderNoteInterface;

/**
 * Interface for saving the checkout order Note
 * to the quote for logged in users
 * @api
 */
interface OrderNoteManagementInterface
{
    /**
     * @param int $cartId
     * @param OrderNoteInterface $orderNote
     * @return string
     */
    public function saveOrderNote(
        $cartId,
        OrderNoteInterface $orderNote
    );

    /**
     * @param int $orderId
     * @param OrderNoteInterface $orderNote
     * @return string
     */
    public function saveOrderNoteAdmin(
        $orderId,
        OrderNoteInterface $orderNote
    );
}
