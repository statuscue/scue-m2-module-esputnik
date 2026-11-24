<?php

namespace Scue\ESputnik\Service\Subscription\Validator;

use Magento\Framework\Message\MessageInterface;
use Magento\Framework\Validator\AbstractValidator;
use Scue\ESputnik\Helper\Config;

class IsSubscriptionIdValid extends AbstractValidator
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function isValid($data)
    {
        $messages = [];

        if (isset($data['id']) && ! empty($data['id'])) {
            if (! in_array($data['id'], $this->config->getAvailableSubscriptionGroupIds())) {
                $messages[] = __('Selected subscription is not available');
            }
        } else {
            $messages[] = __('Please select subscription');
        }

        if (! empty($messages)) {
            $this->_addMessages([
                MessageInterface::TYPE_ERROR => $messages
            ]);
            return false;
        }

        return true;
    }
}