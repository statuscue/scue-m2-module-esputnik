<?php

namespace Scue\ESputnik\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Scue\ESputnik\Model\Broadcast\EnumTemplate;

class Template extends AbstractHelper
{
    public function __construct(private EnumTemplate $template, Context $context)
    {
        parent::__construct($context);
    }

    public function getTemplate(): string
    {
        if ($template = $this->scopeConfig->getValue($this->template->getXmlPath())) {
            return (string)$template;
        }

        return '';
    }
}