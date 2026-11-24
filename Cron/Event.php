<?php

namespace Scue\ESputnik\Cron;

use Scue\ESputnik\Model\Source\Status;
use Scue\ESputnik\Model\ResourceModel\Event\CollectionFactory;
use Scue\ESputnik\Model\ResourceModel\Event\Collection;
use Magento\Framework\Model\ResourceModel\IteratorFactory;
use Magento\Framework\Model\ResourceModel\Iterator;
use Scue\ESputnik\Service\EventManager as Service;
use Scue\ESputnik\Model\Repository\Event as Repository;

class Event
{
    public function __construct(
        private Service $service,
        private IteratorFactory $iteratorFactory,
        private Repository $repository,
        private CollectionFactory $eventCollectionFactory,
    ) {
    }

    public function clean()
    {
        $this->repository->clean();
    }

    public function events(): void
    {
        /**
         * @var Iterator $iterator
         * @var Collection $collection
         */
        $iterator = $this->iteratorFactory->create();
        $collection = $this->eventCollectionFactory->create();
        $collection->addStatusFilter(Status::NEW->value);

        $iterator->walk(
            $collection->getSelect(), [
                [$this, 'eventCallback']
            ]
        );
    }

    public function eventCallback(array $args)
    {
        $row = $args['row'];
        $model = $this->repository->get($row['id']);

        $this->service->send($model, false);
    }
}