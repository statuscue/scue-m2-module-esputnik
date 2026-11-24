<?php

namespace Scue\ESputnik\Service\Subscription\Mapper;

use Scue\ESputnik\Service\Subscription\Mapper;
use Scue\ESputnik\Helper\Config;
use Magento\Framework\ObjectManager\TMapFactory;

class Groups extends Mapper
{
    private $config;

    public function __construct(
        Config $config,
        TMapFactory $mapFactory,
        array $handlers = [],
        array $availableFields = []
    ) {
        $this->config = $config;
        parent::__construct($mapFactory, $handlers, $availableFields);
    }

    public function handle(array $request): array
    {
        $groups[] = $this->config->getSubscriptionGroupName($request['id']);

        return compact('groups');
    }
}