<?php

namespace Scue\ESputnik\Service\Broadcast;

use Scue\ESputnik\Api\BroadcastChannel;

abstract class Channel implements BroadcastChannel
{
    protected $context;
    protected $configNamespace;

    public function __construct(ChannelContext $context)
    {
        $this->context = $context;
    }

    public function getConfig(?string $field = null)
    {
        $configPath = $field
            ? sprintf('%s/%s', $this->configNamespace, $field)
            : $this->configNamespace;

        return $this->context->getConfig()->getBroadcastConfig($configPath);
    }
}