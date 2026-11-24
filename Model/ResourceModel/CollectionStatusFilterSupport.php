<?php

namespace Scue\ESputnik\Model\ResourceModel;

trait CollectionStatusFilterSupport
{
    public function addStatusFilter(...$statuses): self
    {
        return $this->addFieldToFilter('status', ['in' => $statuses]);
    }
}