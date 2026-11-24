<?php

namespace Scue\ESputnik\Service;

use Throwable;
use Scue\ESputnik\Model\Source\Status;
use Scue\ESputnik\Model\Source\Event as EnumEvent;
use Scue\ESputnik\Service\EventManager\Context;
use Scue\ESputnik\Api\Data\Event as IEvent;

class EventManager
{
    public function __construct(protected readonly Context $context)
    {}

    public function create(EnumEvent $event, array $params = [])
    {
        $eventModel = $this->context->eventFactory->create($event->value, $params);
        $object = $this->context->repository->create($event, $eventModel->getEntityId(), $eventModel->getParams());

        if (! $eventModel->isAvailable(compact('object'))) {
            $this->context->repository->update($object, [
                'status' => Status::ERROR->value,
                'response' => [
                    'validation_errors' => $eventModel->getValidationErrors()
                ]
            ]);
        }
    }

    public function send(IEvent $event, bool $throw = true)
    {
        $status = Status::ERROR;
        $client = $this->context->client;

        try {
            $client->post($event->getEntityType(), json_encode($event->getParams()));

            $response = $client->getBody();
            $response = json_decode($response, true);

            if ($client->isSuccessful()) {
                $status = Status::SUCCESS;
            }
        } catch (Throwable $throwable) {
            $response = [
                'exception' => $throwable->getMessage()
            ];
        }

        $this->context->repository->update($event, [
            'status' => $status->value,
            'response' => $response
        ]);

        if ($throw && isset($throwable)) {
            throw $throwable;
        }
    }
}