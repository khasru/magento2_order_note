<?php

namespace Scriptlodge\OrderNote\Plugin\Block\Adminhtml;

use Magento\Backend\Block\Template\Context;
use Scriptlodge\OrderNote\Model\Data\OrderNote;
use Magento\Sales\Block\Adminhtml\Order\View\Info as ViewInfo;

class SalesOrderViewInfo
{

    public function __construct(
        Context $context
    )
    {
        $this->_urlBuilder = $context->getUrlBuilder();

    }

    /**
     * @param ViewInfo $subject
     * @param string $result
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterToHtml(
        ViewInfo $subject,
        $result
    )
    {
        $noteBlock = $subject->getLayout()
            ->getBlock('sl_order_notes');
        $orderId = $subject->getOrder()->getData('entity_id');
        if ($noteBlock !== false) {
            $editUrl = $this->getOrderAttributeEditUrl($orderId);
            $isAllowedToAddNote = $this->getItemInternalOrderNote($subject->getOrder());
            $noteBlock->setOrderNote($subject->getOrder()
                ->getData(OrderNote::FIELD_NAME));
            $noteBlock->setOrderId($subject->getOrder()
                ->getData('entity_id'));
            $noteBlock->setNoteUrl($editUrl);
            $noteBlock->setIsAllowedToAddNote($isAllowedToAddNote);
            // $result = $result . $noteBlock->toHtml();
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getOrderAttributeEditUrl($orderId)
    {
        return $this->getUrl(
            'ordernote/note/edit',
            ['order_id' => $orderId]
        );
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->_urlBuilder->getUrl($route, $params);
    }

    public function getItemInternalOrderNote($order)
    {
        $showOrderNote = false;
        /*  if (!$this->isEnabled()) {
             return $showOrderNote;
         }*/

        $items = $order->getAllVisibleItems();
        foreach ($items as $item) {
            $internalOrderNote = (int)$item->getProduct()->getInternalOrderNote();
            if ($internalOrderNote == 1) {
                return $showOrderNote = true;
            }
        }

        return $showOrderNote;
    }
}
