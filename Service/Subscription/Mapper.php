<?php

namespace Scue\ESputnik\Service\Subscription;

use Scue\ESputnik\Api\Http\MapperInterface;
use Magento\Framework\ObjectManager\TMapFactory;

class Mapper implements MapperInterface
{
    protected $availableFields = [];

    /**
     * @var MapperInterface[]
     */
    private $handlers = [];

    public function __construct(TMapFactory $mapFactory, array $handlers = [], array $availableFields = [])
    {
        $this->handlers = $mapFactory->create([
            'array' => $handlers,
            'type' => MapperInterface::class
        ]);
        $this->availableFields = array_merge($this->availableFields, $availableFields);
    }

    public function handle(array $request): array
    {
        $data = [];

        foreach ($this->handlers as $mapper) {
            $data = array_merge($data, $mapper->handle($request));
        }

        return $data;
    }

    protected function filter(array $request): array
    {
        if (empty($this->availableFields)) {
            return $request;
        }

        $data = [];

        foreach ($request as $key => $value) {
            if (in_array($key, $this->availableFields)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}