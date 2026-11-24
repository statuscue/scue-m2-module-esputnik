<?php

namespace Scue\ESputnik\Service\EventManager\Validator;

use Magento\Framework\Message\MessageInterface;
use Magento\Framework\Validator\AbstractValidator;
use Scue\ESputnik\Model\ResourceModel\Event\CollectionFactory;

class IsOrdersEventSent extends AbstractValidator
{
    public function __construct(private CollectionFactory $collectionFactory)
    {
    }

    public function isValid($data)
    {
        if (isset($data['status'], $data['entity'])) {
            if ($this->isAlreadySent($data['status'], $data['entity'], $data['object'] ?? null)) {
                $messages[] = __('This event is already sent');
            }
        } else {
            $messages[] = __('Please provide order and new status');
        }

        if (! empty($messages)) {
            $this->_addMessages([
                MessageInterface::TYPE_ERROR => $messages
            ]);
            return false;
        }

        return true;
    }

    protected function isAlreadySent(string $status, $order, $created = null): bool
    {
        /**
         * @var \Scue\ESputnik\Model\ResourceModel\Event\Collection $collection
         */
        $collection = $this->collectionFactory->create();
        $collection->getSelect()->where("JSON_CONTAINS(`params`, '\"{$status}\"', '$.orders[0].status')");
        $collection->getSelect()->where("JSON_CONTAINS(`params`, '\"{$order->getIncrementId()}\"', '$.orders[0].externalOrderId')");

        if ($created && $created->getId()) {
            $collection->addFieldToFilter('id', ['neq' => $created->getId()]);
        }

        return $collection->getSize() > 0;
    }
}