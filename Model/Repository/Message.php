<?php

namespace Scue\ESputnik\Model\Repository;

use Scue\ESputnik\Model\MessageFactory;
use Scue\ESputnik\Model\ResourceModel\Message as Resource;
use Scue\ESputnik\Model\Source\Status;
use Scue\ESputnik\Helper\Config;

class Message
{
    public function __construct(
        private Config $config,
        private MessageFactory $messageFactory,
        private Resource $resource
    ) {
    }

    public function get(int $id)
    {
        $model = $this->messageFactory->create();
        $this->resource->load($model, $id);

        return $model;
    }

    public function create(string $channel, $contact, string $message, array $params = [], Status $status = Status::NEW)
    {
        $model = $this->messageFactory->create()->addData([
            'status' => $status->value,
            'channel' => $channel,
            'contact' => (array)$contact,
            'message' => $message,
            'params' => $params,
            'response' => []
        ]);
        $this->resource->save($model);

        return $model;
    }

    public function update($model, array $data)
    {
        $model->addData($data);
        $this->resource->save($model);
    }

    public function clean(?int $days = null)
    {
        if (! $days) {
            $days = $this->config->getCleanInterval();
        }

        $this->resource->clean($days);
    }
}