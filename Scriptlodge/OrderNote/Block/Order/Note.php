<?php
namespace Scriptlodge\OrderNote\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Scriptlodge\OrderNote\Model\Data\OrderNote;
use Magento\Framework\Registry;
use Magento\Sales\Model\Order;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Scriptlodge\OrderNote\Model\OrderNoteConfig;

class Note extends Template
{
    /**
     * @var OrderNoteConfig
     */
    protected $orderNoteConfig;

    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @param    Context $context
     * @param    Registry $registry
     * @param    OrderNoteConfig $orderNoteConfig
     * @param   array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        OrderNoteConfig $orderNoteConfig,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->orderNoteConfig = $orderNoteConfig;
        $this->_isScopePrivate = true;
        $this->_template = 'order/view/note.phtml';
        parent::__construct($context, $data);

    }

    /**
     * Check if show order note to customer account
     *
     * @return bool
     */
    public function isShowNoteInAccount()
    {
        return $this->orderNoteConfig->isShowNoteInAccount();
    }

    /**
     * Get Order
     *
     * @return array|null
     */
    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }

    /**
     * Get Order Note
     *
     * @return string
     */
    public function getOrderNote()
    {
        return trim($this->getOrder()->getData(OrderNote::FIELD_NAME));
    }

    /**
     * Retrieve html Note
     *
     * @return string
     */
    public function getOrderNoteHtml()
    {
        return nl2br($this->escapeHtml($this->getOrderNote()));
    }

    /**
     * Check if has order Note
     *
     * @return bool
     */
    public function hasOrderNote()
    {
        return strlen($this->getOrderNote()) > 0;
    }
}
