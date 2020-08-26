<?php

namespace Scriptlodge\OrderNote\Controller\Adminhtml\Note;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Sales\Model\OrderFactory;


class Edit extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    protected $resultPageFactory;
    protected $resultJsonFactory;
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::comment';


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        OrderFactory $orderFactory

    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->orderFactory = $orderFactory;
        // exit('kkkk');
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    public function execute()
    {
        try {
            $params = $this->getRequest()->getParams();
            if (isset($params['order_id'])) {
                $note = trim($params['note']);
                $orderModel = $this->orderFactory->create();
                $orderModel->load($params['order_id']);
                $orderModel->setOrderNote($note)->save();

                $resultJson = $this->resultJsonFactory->create();
                $response = ['error' => false, 'message' => __('Sucessfully added order note.')];
                $resultJson->setData($response);
                return $resultJson;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('The order id is missing. Refresh try again.')
                );
            }


        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $response = ['error' => true, 'message' => $e->getMessage()];
        } catch (\Exception $e) {

            $response = ['error' => true, 'message' => __('We cannot add order note.')];
        }
        if (is_array($response)) {
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($response);
            return $resultJson;
        }
        return $this->resultRedirectFactory->create()->setPath('sales/*/');

    }

    protected function _isAllowed()
    {
        return true;
    }
}
