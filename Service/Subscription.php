<?php

namespace Scue\ESputnik\Service;

use Magento\Framework\Validator\Exception;
use Scue\ESputnik\Api\ValidatorFactoryInterface;
use Scue\ESputnik\Api\Subscription as ISubscription;
use Scue\ESputnik\Api\Http\ClientInterface;
use Scue\ESputnik\Api\Http\MapperInterface;

class Subscription implements ISubscription
{
    const STATUS_SUBSCRIBED = 'subscribed';

    private $validatorFactory;
    private $client;
    private $mapper;

    public function __construct(
        ValidatorFactoryInterface $validatorFactory,
        ClientInterface           $client,
        MapperInterface           $mapper
    ) {
        $this->validatorFactory = $validatorFactory;
        $this->client = $client;
        $this->mapper = $mapper;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe($subscription)
    {
        $this->validate($subscription);
        $this->client->post('v1/contact/subscribe', $this->mapper->handle($subscription));

        if ($response = $this->client->getBody()) {
            $response = json_decode($response, true);
        } else {
            $response = ['message' => __('Empty response')];
        }

        return $response;
    }

    private function validate($subscription)
    {
        /**
         * @var \Magento\Framework\Validator $validator
         */
        $validator = $this->validatorFactory->create();

        if (! $validator->isValid($subscription)) {
            throw new Exception(null, null, $validator->getMessages());
        }
    }
}