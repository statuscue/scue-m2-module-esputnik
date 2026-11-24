<?php

namespace Scue\ESputnik\Console;

use Symfony\Component\Console\Input\InputArgument;

enum BroadcastArguments
{
    case CHANNEL;
    case TEMPLATE;

    public function getName(): string
    {
        return match($this) {
            self::CHANNEL => 'channel',
            self::TEMPLATE => 'template'
        };
    }

    public function getMode(): ?int
    {
        return match($this) {
            default => InputArgument::REQUIRED
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::CHANNEL => 'channel',
            self::TEMPLATE => 'template'
        };
    }

    public function getDefault()
    {
        return match($this) {
            default => null
        };
    }
}