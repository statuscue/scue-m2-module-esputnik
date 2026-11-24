<?php

namespace Scue\ESputnik\Validation\Validator;

use Magento\Framework\Message\MessageInterface;
use Magento\Framework\Validator\AbstractValidator;
use Scue\ESputnik\Helper\Config;

class ModuleEnabled extends AbstractValidator
{
    public function __construct(private Config $config)
    {
    }

    public function isValid($data)
    {
        if (! $this->config->getGeneralConfig('enable')) {
            $messages[] = __('Module is disabled');
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