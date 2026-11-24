<?php

namespace Scue\ESputnik\Service\Broadcast;

use InvalidArgumentException;
use Scue\ESputnik\Api\BroadcastChannel;
use Magento\Framework\ObjectManager\TMapFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\ObjectManager\FactoryInterface;
use Magento\Framework\ObjectManagerInterface;

class ChannelFactory implements OptionSourceInterface, FactoryInterface
{
    /**
     * @var BroadcastChannel[]
     */
    private $channels;

    public function __construct(
        private ObjectManagerInterface $objectManager,
        TMapFactory $mapFactory,
        array $channels = []
    ) {
        $this->channels = $mapFactory->create([
            'array' => $channels,
            'type' => BroadcastChannel::class
        ]);
    }

    public function toOptionArray()
    {
        $options = [];

        foreach ($this->channels as $value => $channel) {
            $options[] = [
                'value' => $value,
                'label' => $channel->getName()
            ];
        }

        return $options;
    }

    public function create($requestedType, array $arguments = [])
    {
        if (! isset($this->channels[$requestedType])) {
            throw new InvalidArgumentException(__('Unknown broadcasting channel: %1', $requestedType));
        }

        return $this->channels[$requestedType];
    }

    public function setObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }
}