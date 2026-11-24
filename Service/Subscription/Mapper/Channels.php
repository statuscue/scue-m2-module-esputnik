<?php

namespace Scue\ESputnik\Service\Subscription\Mapper;

use Scue\ESputnik\Service\Subscription\Mapper;

class Channels extends Mapper
{
    public function handle(array $request): array
    {
        $data[] = [
            'type' => 'email',
            'value' => $request['email']
        ];

        if (isset($request['sms'])) {
            $data[] = [
                'type' => 'sms',
                'value' => $request['sms']
            ];
        }

        return [
            'channels' => $data
        ];
    }
}