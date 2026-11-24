<?php

namespace Scue\ESputnik\Model\Source;

enum Status: string
{
    case NEW = 'new';
    case ERROR = 'error';
    case SUCCESS = 'success';
}