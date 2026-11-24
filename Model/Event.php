<?php

namespace Scue\ESputnik\Model;

use Magento\Framework\Model\AbstractModel;
use Scue\ESputnik\Api\Data\Event as IEvent;

class Event extends AbstractModel implements IEvent
{
    protected $_eventPrefix = 'esputnik_event';
    protected $_eventObject = 'event';

    protected function _construct()
    {
        $this->_init(ResourceModel\Event::class);
    }

    public function getEntityType(): string
    {
        return $this->getData('entity_type');
    }

    public function getParams(): array
    {
        if ($params = $this->getData('params')) {
            if (! is_array($params)) {
                $params = json_decode($params, true);
            }

            return $params;
        }

        return [];
    }
}