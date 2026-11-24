<?php

namespace Scue\ESputnik\Model\Repository;

use Scue\ESputnik\Model\EventFactory;
use Scue\ESputnik\Model\ResourceModel\Event as Resource;
use Scue\ESputnik\Model\Source\Status;
use Scue\ESputnik\Helper\Config;
use Scue\ESputnik\Model\Source\Event as EnumEvent;

class Event
{
    public function __construct(
        private Config $config,
        private EventFactory $eventFactory,
        private Resource $resource
    ) {
    }

    public function get(int $id)
    {
        $model = $this->eventFactory->create();
        $this->resource->load($model, $id);

        return $model;
    }

    public function create(EnumEvent $event, ?int $entityId, array $params = [], Status $status = Status::NEW)
    {
        $model = $this->eventFactory->create()->addData([
            'status' => $status->value,
            'entity_type' => $event->value,
            'entity_id' => $entityId,
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