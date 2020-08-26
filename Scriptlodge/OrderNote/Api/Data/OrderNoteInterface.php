<?php

namespace Scriptlodge\OrderNote\Api\Data;

/**
 * Interface OrderNoteInterface
 * @api
 */
interface OrderNoteInterface
{
    /**
     * @return string|null
     */
    public function getNote();

    /**
     * @param string $note
     * @return null
     */
    public function setNote($note);
}
