<?php

namespace Scriptlodge\OrderNote\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveOrderBeforeSalesModelQuoteObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;


    /**
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     */
    public function __construct(
        \Magento\Framework\DataObject\Copy $objectCopyService
    )
    {
        $this->objectCopyService = $objectCopyService;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /* @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');
       /* print_r($quote->getEntityId());
        print_r($observer->getEvent()->getData());
        exit('kk');*/
        $order->setOrderNote($quote->getOrderNote());

        $this->objectCopyService->copyFieldsetToTarget('sales_convert_quote', 'to_order', $quote, $order);

        return $this;
    }
}
