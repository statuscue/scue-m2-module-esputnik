<?php

namespace Scue\ESputnik\Api\Http;

interface MapperInterface
{
    public function handle(array $request): array;
}
