<?php

namespace Scue\ESputnik\Service\Broadcast;

use Scue\ESputnik\Api\Http\ClientInterface;
use Scue\ESputnik\Helper\Config;

class ChannelContext
{
    private $client;
    private $config;

    public function __construct(
        ClientInterface $client,
        Config $config
    ) {
        $this->client = $client;
        $this->config = $config;
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }
}