<?php

namespace Scue\ESputnik\Service;

use Scue\ESputnik\Api\Http\MapperInterface;
use Scue\ESputnik\Api\Event as IEvent;
use Magento\Framework\DataObject;
use Scue\ESputnik\Validator\Factory as ValidatorFactory;

class Event extends DataObject implements IEvent
{
    protected $validator;

    public function __construct(
        private MapperInterface $mapper,
        private ?ValidatorFactory $validatorFactory = null,
        array $data = []
    ) {
        parent::__construct($data);
    }

    public function getEntityId(): ?int
    {
        if ($entity = $this->getData('entity') and is_object($entity)) {
            return $entity->getId();
        }

        return null;
    }

    public function getParams(): array
    {
        return $this->mapper->handle($this->getData());
    }

    public function isAvailable(array $params = []): bool
    {
        if ($this->validatorFactory === null) {
            return true;
        }

        $this->validator = $this->validatorFactory->create();
        $params = array_merge($this->getData(), $params);

        return $this->validator->isValid($params);
    }

    public function getValidationErrors(): array
    {
        if ($this->validator === null) {
            return [];
        }

        return $this->validator->getMessages();
    }
}