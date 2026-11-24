<?php

namespace Scue\ESputnik\Cron;

use Scue\ESputnik\Model\Source\Status;
use Scue\ESputnik\Model\ResourceModel\Message\CollectionFactory;
use Scue\ESputnik\Model\ResourceModel\Message\Collection;
use Magento\Framework\Model\ResourceModel\IteratorFactory;
use Magento\Framework\Model\ResourceModel\Iterator;
use Scue\ESputnik\Service\Broadcast as Service;
use Scue\ESputnik\Model\Repository\Message as Repository;

class Message
{
    public function __construct(
        private Service $service,
        private CollectionFactory $collectionFactory,
        private IteratorFactory $iteratorFactory,
        private Repository $repository
    ) {
    }

    public function clean()
    {
        $this->repository->clean();
    }

    public function sms(): void
    {
        $this->processCollection('sms');
    }

    public function email(): void
    {
        $this->processCollection('email');
    }

    public function callback(array $args)
    {
        $row = $args['row'];
        $model = $this->repository->get($row['id']);

        $this->service->sendModel($model, false);
    }

    protected function processCollection(string $channel): void
    {
        /**
         * @var Collection $collection
         */
        $collection = $this->collectionFactory->create();
        $collection->addStatusFilter(Status::NEW->value);
        $collection->addChannelFilter($channel);
        $collection->getSelect()->order('id ASC');

        /**
         * @var Iterator $iterator
         */
        $iterator = $this->iteratorFactory->create();

        $iterator->walk(
            $collection->getSelect(), [
                [$this, 'callback']
            ]
        );
    }
}