<?php

namespace Scue\ESputnik\Service\Broadcast\Channel;

use Scue\ESputnik\Service\Broadcast\Channel;
use Scue\ESputnik\Service\Broadcast\ChannelContext;
use Psr\Log\LoggerInterface;

class Sms extends Channel
{
    public function __construct(private LoggerInterface $logger, ChannelContext $context)
    {
        parent::__construct($context);
    }

    protected $configNamespace = 'sms';

    public function getName(): string
    {
        return __('Sms');
    }

    public function send($contact, string $text, array $additional = [])
    {
        $params['from'] = $this->getConfig('sender_name');
        $params['text'] = $text;
        $params['phoneNumbers'] = (array)$contact;

        if ($this->getConfig('debug')) {
            $this->logger->debug('SMS', $params);
            $response = [
                'message' => 'SMS is saved in esputnik.log file'
            ];
        } else {
            $this->context->getClient()->post('api/v1/message/sms', json_encode($params));

            if ($response = $this->context->getClient()->getBody()) {
                $response = json_decode($response, true);
            } else {
                $response = [];
            }
        }

        return $response;
    }
}