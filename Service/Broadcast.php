<?php

namespace Scue\ESputnik\Service;

use Throwable;
use Scue\ESputnik\Model\Source\Status;
use Scue\ESputnik\Service\Broadcast\ChannelFactory;
use Scue\ESputnik\Model\Repository\Message as Repository;
use Scue\ESputnik\Api\Data\Broadcast as IBroadcast;

class Broadcast
{
    public function __construct(
        private ChannelFactory $channelFactory,
        private Repository $repository
    ) {
    }

    public function send(string $channel, $contact, string $message, array $params = [])
    {
        if (isset($params['queue'])) {
            $instantSend = ! $params['queue'];
        } else {
            $instantSend = false;
        }

        if (empty($params['id'])) {
            $model = $this->repository->create($channel, $contact, $message, $params);
        } else {
            $model = $this->repository->get($params['id']);
        }

        if ($instantSend) {
            $this->sendModel($model);
        }
    }

    public function sendModel(IBroadcast $model, bool $throwException = true)
    {
        try {
            $channel = $this->channelFactory->create($model->getChannel());
            $response = $channel->send($model->getContact(), $model->getMessage(), $model->getParams());
            $this->repository->update($model, [
                'status' => Status::SUCCESS->value,
                'response' => (array)$response
            ]);
        } catch (Throwable $throwable) {
            $this->repository->update($model, [
                'status' => Status::ERROR->value,
                'response' => [
                    'exception' => $throwable->getMessage()
                ]
            ]);

            if ($throwException) {
                throw $throwable;
            }
        }
    }
}