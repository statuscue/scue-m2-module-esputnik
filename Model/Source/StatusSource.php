<?php

namespace Scue\ESputnik\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class StatusSource implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return array_map(fn ($status) => [
            'label' => $status->value,
            'value' => $status->value
        ], Status::cases());
    }
}