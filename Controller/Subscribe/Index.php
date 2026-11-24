<?php

namespace Scue\ESputnik\Controller\Subscribe;

use Scue\ESputnik\Controller\Context;
use Scue\ESputnik\Controller\ESputnik;
use Scue\ESputnik\Api\Subscription;

class Index extends ESputnik
{
    private $subscription;

    public function __construct(Subscription $subscription, Context $context)
    {
        $this->subscription = $subscription;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->process(function () {
            $params = $this->context->getRequest()->getParam('subscription', []);
            $this->subscription->subscribe($params);
        }, __('You have been successfully subscribed'), __('Something went wrong'));

        return $this->context->getPageFactory()->create();
    }
}