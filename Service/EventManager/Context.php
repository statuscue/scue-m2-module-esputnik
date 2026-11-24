<?php

namespace Scue\ESputnik\Service\EventManager;

use Scue\ESputnik\Model\Repository\Event as Repository;
use Scue\ESputnik\Api\Http\ClientInterface;

class Context
{
    public function __construct(
        public readonly EventFactory $eventFactory,
        public readonly Repository $repository,
        public readonly ClientInterface $client
    ) {
    }
}