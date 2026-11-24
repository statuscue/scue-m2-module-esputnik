<?php

namespace Scue\ESputnik\Service\EventManager;

use InvalidArgumentException;
use Magento\Framework\ObjectManager\FactoryInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\ObjectManager\TMapFactory;
use Scue\ESputnik\Api\Event;

class EventFactory implements FactoryInterface
{
    private $events;

    public function __construct(
        private ObjectManagerInterface $objectManager,
        TMapFactory $mapFactory,
        array $events = []
    ) {
        $this->events = $mapFactory->create([
            'array' => $events,
            'type' => Event::class
        ]);
    }

    public function setObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param $requestedType
     * @param array $arguments
     * @return Event
     */
    public function create($requestedType, array $arguments = [])
    {
        if (! isset($this->events[$requestedType])) {
            throw new InvalidArgumentException(__('Unknown event: %1', $requestedType));
        }

        $event = clone $this->events[$requestedType];
        $event->addData($arguments);

        return $event;
    }
}