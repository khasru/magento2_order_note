<?php

namespace Scriptlodge\OrderNote\Model;

use Scriptlodge\OrderNote\Api\OrderNoteManagementInterface;
use Scriptlodge\OrderNote\Model\Data\OrderNote;
use Scriptlodge\OrderNote\Model\OrderNoteConfig;
use Scriptlodge\OrderNote\Api\Data\OrderNoteInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\ValidatorException;
use Magento\Store\Model\ScopeInterface;

class OrderNoteManagement implements OrderNoteManagementInterface
{
    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param CartRepositoryInterface $quoteRepository
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string $ordernote
     * @throws ValidatorException
     */
    protected function validateNote($ordernote)
    {
        $maxLength = $this->scopeConfig->getValue(
            OrderNoteConfig::XML_PATH_GENERAL_MAX_LENGTH,
            ScopeInterface::SCOPE_STORE
        );

        if ($maxLength && (mb_strlen($ordernote) > $maxLength)) {
            throw new ValidatorException(
                __('The order note entered exceeded the limit')
            );
        }
    }

    /**
     * @param int $cartId
     * @param OrderNoteInterface $orderNote
     * @return mixed
     */
    public function saveOrderNote($cartId, OrderNoteInterface $orderNote)
    {
        $quote = $this->quoteRepository->getActive($cartId);

        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(
                __('Cart %1 doesn\'t contain products', $cartId)
            );
        }

        $note = $orderNote->getNote();

        $this->validateNote($note);

        try {
            $quote->setData(OrderNote::FIELD_NAME, strip_tags($note));

            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The order comment could not be saved')
            );
        }

        return $note;
    }

    /**
     * @param int $orderId
     * @param OrderNoteInterface $orderNote
     * @return mixed
     */
    public function saveOrderNoteAdmin($orderId, OrderNoteInterface $orderNote)
    {
        exit('kkk');
       /* $quote = $this->quoteRepository->getActive($cartId);

        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(
                __('Cart %1 doesn\'t contain products', $cartId)
            );
        }

        $note = $orderNote->getNote();

        $this->validateNote($note);

        try {
            $quote->setData(OrderNote::FIELD_NAME, strip_tags($note));

            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The order comment could not be saved')
            );
        }

        return $note;*/
    }
}
