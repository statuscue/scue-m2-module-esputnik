<?php

namespace Scue\ESputnik\Controller;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Message\ManagerInterface;

class Context
{
    private $request;
    private $pageFactory;
    private $messageManager;

    public function __construct(
        RequestInterface $request,
        PageFactory $pageFactory,
        ManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->pageFactory = $pageFactory;
        $this->messageManager = $messageManager;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getPageFactory(): PageFactory
    {
        return $this->pageFactory;
    }

    public function getMessageManager(): ManagerInterface
    {
        return $this->messageManager;
    }
}