<?php

namespace Scue\ESputnik\Api;

use Magento\Framework\Validator\Exception;;

interface Subscription
{
    /**
     * @api
     * @param mixed $subscription
     * @return array
     * @throws Exception
     */
    public function subscribe($subscription);
}