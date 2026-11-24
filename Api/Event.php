<?php

namespace Scue\ESputnik\Api;

interface Event
{
    public function getEntityId(): ?int;
    public function getParams(): array;
    public function isAvailable(array $params = []): bool;
    public function getValidationErrors(): array;
}