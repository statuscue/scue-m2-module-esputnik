<?php

namespace Scue\ESputnik\Service\Broadcast\Channel;

use Scue\ESputnik\Service\Broadcast\Channel;

class Email extends Channel
{
    protected $configNamespace = 'email';

    public function getName(): string
    {
        return __('Email');
    }

    public function send($contact, string $text, array $additional = [])
    {
        $params['from'] = sprintf('"%s" <%s>', $this->getConfig('sender_name'), $this->getConfig('sender_address'));
        $params['subject'] = $additional['subject'];
        $params['htmlText'] = $text;
        $params['emails'] = (array)$contact;

        $this->context->getClient()->post('api/v1/message/email', json_encode($params));

        if ($response = $this->context->getClient()->getBody()) {
            $response = json_decode($response, true);
        } else {
            $response = [];
        }

        return $response;
    }
}