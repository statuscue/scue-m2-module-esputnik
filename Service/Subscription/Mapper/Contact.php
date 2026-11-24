<?php

namespace Scue\ESputnik\Service\Subscription\Mapper;

use Scue\ESputnik\Service\Subscription\Mapper;

class Contact extends Mapper
{
    protected $availableFields = [
        'firstName',
        'lastName',
        'channels',
        'address',
        'fields',
        'addressBookId',
        'id',
        'externalCustomerId',
        'contactKey',
        'ordersInfo',
        'groups',
        'languageCode',
        'timeZone'
    ];

    public function handle(array $request): array
    {
        $contact = parent::handle($request);

        if (isset($request['additional']) && is_array($request['additional'])) {
            $contact = array_merge($contact, $this->filter($request['additional']));
        }

        return compact('contact');
    }
}