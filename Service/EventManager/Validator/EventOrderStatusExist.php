<?php

namespace Scue\ESputnik\Service\EventManager\Validator;

use Magento\Framework\Message\MessageInterface;
use Magento\Framework\Validator\AbstractValidator;
use Scue\ESputnik\Helper\Config;

class EventOrderStatusExist extends AbstractValidator
{
    public function __construct(private Config $config)
    {
    }

    public function isValid($data)
    {
        if (! empty($data['status'])) {
            if (! $this->config->getEventStatusByOrderStatus($data['status'])) {
                $messages[] = __('No status mapping found');
            }
        } else {
            $messages[] = __('Status was not provided');
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