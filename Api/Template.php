<?php

namespace Scue\ESputnik\Api;

interface Template
{
    public function __toString(): string;
    public function getTemplate(): ?string;
}