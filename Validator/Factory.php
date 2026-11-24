<?php

namespace Scue\ESputnik\Validator;

use Scue\ESputnik\Api\ValidatorFactoryInterface as IValidatorFactory;
use Magento\Framework\Validator\ValidatorInterface;
use Magento\Framework\Validator\Factory as ValidatorFactory;

class Factory implements IValidatorFactory
{
    const ENTITY = 'esputnik';

    const GROUP_SUBSCRIPTION = 'subscription';
    const GROUP_ORDERS_EVENT = 'orders_event';

    /**
     * @var \Magento\Framework\Validator\Factory
     */
    private $factory;
    private $group;
    private $entity;

    public function __construct(string $group, ValidatorFactory $factory, string $entity = self::ENTITY)
    {
        $this->entity = $entity;
        $this->group = $group;
        $this->factory = $factory;
    }

    public function create(): ValidatorInterface
    {
        return $this->factory->createValidator($this->entity, $this->group);
    }
}