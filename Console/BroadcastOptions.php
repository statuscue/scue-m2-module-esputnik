<?php

namespace Scue\ESputnik\Console;

use Symfony\Component\Console\Input\InputOption;

enum BroadcastOptions
{
    case CONTACT;
    case CODE;
    case QUEUE;

    public function getName(): string
    {
        return match ($this) {
            self::CONTACT => 'contact',
            self::CODE => 'code',
            self::QUEUE => 'queue'
        };
    }

    public function getMode(): ?int
    {
        return match ($this) {
            default => null,
            self::CONTACT => InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY,
            self::CODE, self::QUEUE => InputOption::VALUE_OPTIONAL
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            default => '',
            self::CONTACT => 'List of contacts broadcast to'
        };
    }

    public function getDefault()
    {
        return match($this) {
            default => null,
            self::QUEUE => 0
        };
    }

    public function getShortcut(): ?string
    {
        return match($this) {
            default => null
        };
    }

    public function isIncludedInParams(): bool
    {
        return match ($this) {
            default => true,
            self::CONTACT, self::CODE => false
        };
    }
}