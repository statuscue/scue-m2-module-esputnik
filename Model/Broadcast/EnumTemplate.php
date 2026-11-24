<?php

namespace Scue\ESputnik\Model\Broadcast;

enum EnumTemplate: string
{
    case SMS_CUSTOMER_AUTHORIZATION = 'customer_registration';
    case SMS_CUSTOMER_REGISTRATION = 'customer_authorization';

    public function getXmlPath(): ?string
    {
        return match ($this) {
            default => null,
            self::SMS_CUSTOMER_AUTHORIZATION => 'esputnik/broadcast/sms/templates/authorization',
            self::SMS_CUSTOMER_REGISTRATION => 'esputnik/broadcast/sms/templates/registration'
        };
    }
}