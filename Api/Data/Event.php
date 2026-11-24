<?php

namespace Scue\ESputnik\Api\Data;

interface Event
{
    public function getEntityType(): string;
    public function getParams(): array;
}
