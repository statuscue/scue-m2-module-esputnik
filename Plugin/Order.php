<?php

namespace Scue\ESputnik\Plugin;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\Order as OrderModel;
use Scue\ESputnik\Service\EventManager;
use Scue\ESputnik\Model\Source\Event as EnumEvent;

class Order
{
    public function __construct(
        private EventManager $eventManager,
        private LoggerInterface $logger
    ) {
    }

    public function beforeSetStatus(OrderModel $order, $status)
    {
        try {
            $this->eventManager->create(EnumEvent::ORDERS, ['entity' => $order, 'status' => $status]);
        } catch (Exception $exception) {
            $this->logger->critical($exception, compact('exception'));
        }

        return $status;
    }
}