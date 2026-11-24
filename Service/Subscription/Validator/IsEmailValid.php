<?php

namespace Scue\ESputnik\Service\Subscription\Validator;

use Magento\Framework\Message\MessageInterface;
use Magento\Framework\Validator\AbstractValidator;

class IsEmailValid extends AbstractValidator
{
    public function isValid($data)
    {
        $messages = [];

        if (isset($data['email']) && ! empty($data['email'])) {
            if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $messages[] = __('Email isn\'t valid');
            }
        } else {
            $messages[] = __('Please enter your email');
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