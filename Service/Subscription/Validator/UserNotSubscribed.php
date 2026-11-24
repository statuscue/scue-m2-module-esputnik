<?php

namespace Scue\ESputnik\Service\Subscription\Validator;

use Magento\Framework\Message\MessageInterface;
use Magento\Framework\Validator\AbstractValidator;
use Scue\ESputnik\Api\Repository\ContactRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;

class UserNotSubscribed extends AbstractValidator
{
    private $searchCriteriaBuilder;
    private $filterBuilder;
    private $repository;
    private $availableFilterFields = [
        'email',
        'sms',
        'externalCustomerId',
        'firstname',
        'lastname'
    ];

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        ContactRepository $contactRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->repository = $contactRepository;
    }

    public function isValid($subscription)
    {
        if (empty($subscription['id'])) {
            return $this->makeAnswer([
                MessageInterface::TYPE_ERROR => [
                    __('Subscription is not provided')
                ]
            ]);
        }

        foreach ($this->availableFilterFields as $field) {
            if ($value = $subscription[$field]) {
                $filters[] = $this->filterBuilder
                    ->setField($field)
                    ->setConditionType('eq')
                    ->setValue($value)
                    ->create();
            }
        }

        if (! isset($filters)) {
            return $this->makeAnswer([
                MessageInterface::TYPE_ERROR => [
                    __('Invalid search data was provided')
                ]
            ]);
        }

        $this->searchCriteriaBuilder->addFilters($filters);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        if (! ($contact = $this->repository->findContact($searchCriteria))) {
            return $this->makeAnswer([
                MessageInterface::TYPE_ERROR => [
                    __('Contacts weren\'t found')
                ]
            ]);
        }

        if (! $this->repository->isSubscribedToGroup($contact, $subscription['id'])) {
            return true;
        }

        return $this->makeAnswer([
            MessageInterface::TYPE_WARNING => [
                __('User is already subscribed')
            ]
        ]);
    }

    private function makeAnswer(array $messages)
    {
        $this->_addMessages($messages);
        return ! $this->hasMessages();
    }
}