<?php

namespace Scriptlodge\OrderNote\Model\Data;

use Scriptlodge\OrderNote\Api\Data\OrderNoteInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class OrderNote extends AbstractSimpleObject implements OrderNoteInterface
{
    const FIELD_NAME = 'order_note';

    /**
     * @return string|null
     */
    public function getNote()
    {
        return $this->_get(static::FIELD_NAME);
    }

    /**
     * @param string $note
     * @return $this
     */
    public function setNote($note)
    {
        return $this->setData(static::FIELD_NAME, $note);
    }

}
