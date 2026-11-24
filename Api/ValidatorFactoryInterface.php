<?php

namespace Scue\ESputnik\Api;

use Magento\Framework\Validator\ValidatorInterface;

interface ValidatorFactoryInterface
{
    public function create(): ValidatorInterface;
}