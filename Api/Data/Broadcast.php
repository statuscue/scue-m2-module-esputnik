<?php

namespace Scue\ESputnik\Api\Data;

interface Broadcast
{
    public function getChannel(): string;
    public function getContact(): array;
    public function getMessage(): string;
    public function getParams(): array;
}
