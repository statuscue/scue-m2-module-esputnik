<?php

namespace Scue\ESputnik\Model\Broadcast;

use Magento\Framework\ObjectManager\FactoryInterface;
use Magento\Framework\ObjectManagerInterface;
use Scue\ESputnik\Api\Template;
use Scue\ESputnik\Helper\Config\Template as Config;

class TemplateFactory implements FactoryInterface
{
    public function __construct(private ObjectManagerInterface $objectManager)
    {
    }

    public function setObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create($requestedType, array $arguments = [])
    {
        $template = EnumTemplate::from($requestedType);

        return $this->objectManager->create(Template::class, [
            'variables' => $arguments,
            'config' => $this->objectManager->create(Config::class, compact('template'))
        ]);
    }
}