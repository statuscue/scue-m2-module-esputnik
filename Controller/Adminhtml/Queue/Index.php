<?php

namespace Scue\ESputnik\Controller\Adminhtml\Queue;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Index extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Scue_ESputnik::esputnik';

    public function __construct(private PageFactory $pageFactory, Context $context)
    {
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Scue_Redwood::esputnik');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Queue'));

        return $resultPage;
    }
}
