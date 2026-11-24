<?php

namespace Scue\ESputnik\Api;

interface BroadcastChannel
{
    public function getName(): string;
    public function send($contact, string $text, array $additional = []);
    public function getConfig(?string $field = null);
}