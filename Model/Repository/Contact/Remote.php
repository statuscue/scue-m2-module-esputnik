<?php

namespace Scue\ESputnik\Model\Repository\Contact;

use Scue\ESputnik\Api\Http\ClientInterface;
use Scue\ESputnik\Api\Repository\ContactRepository;
use Scue\ESputnik\Api\Data\Contact;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Remote implements ContactRepository
{
    private $client;
    private $searchCriteriaBuilder;
    private $filterBuilder;

    private $subscriptions = [];
    private $availableListParams = [
        'email',
        'sms',
        'externalCustomerId'
    ];

    public function __construct(
        ClientInterface $client,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->client = $client;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($searchCriteria)
    {
        $params = [];

        foreach ($searchCriteria->getFilterGroups() as $group) {
            foreach ($group->getFilters() as $filter) {
                if (! in_array($filter->getField(), $this->availableListParams)) {
                    continue;
                }

                $params[$filter->getField()] = $filter->getValue();
            }
        }

        $url = 'api/v1/contacts?' . http_build_query($params);
        $this->client->get($url);

        if ($response = $this->client->getBody()) {
            $response = json_decode($response, true);

            if (! empty($response['message'])) {
                $response = [];
            }
        } else {
            $response = [];
        }

        return array_map(function($contact) {
            return new \Scue\ESputnik\Model\Contact($contact);
        }, $response);
    }

    public function findContact($searchCriteria): ?Contact
    {
        $searchCriteria->setCurrentPage(1);
        $searchCriteria->setPageSize(1);
        $contacts = $this->getList($searchCriteria);

        return $contacts ? current($contacts) : null;
    }

    public function getByEmail(string $email): ?Contact
    {
        $filters[] = $this->filterBuilder
            ->setField('email')
            ->setConditionType('eq')
            ->setValue($email)
            ->create();
        $this->searchCriteriaBuilder->addFilters($filters);

        return $this->findContact($this->searchCriteriaBuilder->create());
    }

    public function getByTelephone($telephone): ?Contact
    {
        $filters[] = $this->filterBuilder
            ->setField('sms')
            ->setConditionType('eq')
            ->setValue($telephone)
            ->create();
        $this->searchCriteriaBuilder->addFilters($filters);

        return $this->findContact($this->searchCriteriaBuilder->create());
    }

    public function getByExternalId($id): ?Contact
    {
        $filters[] = $this->filterBuilder
            ->setField('externalCustomerId')
            ->setConditionType('eq')
            ->setValue($id)
            ->create();
        $this->searchCriteriaBuilder->addFilters($filters);

        return $this->findContact($this->searchCriteriaBuilder->create());
    }

    public function getSubscriptions(Contact $contact): ?array
    {
        if (! isset($this->subscriptions[$contact->getContactId()])) {
            $url = sprintf('api/v1/contact/%s', $contact->getContactId());
            $this->client->get($url);

            if ($response = $this->client->getBody()) {
                $response = json_decode($response, true);
            }

            $this->subscriptions[$contact->getContactId()] = $response;
        }

        return $this->subscriptions[$contact->getContactId()];
    }

    public function isSubscribedToGroup(Contact $contact, $group): bool
    {
        $contactInfo = $this->getSubscriptions($contact);

        if ($contactInfo && ! empty($contactInfo['groups'])) {
            return in_array($group, array_column($contactInfo['groups'], 'id'));
        }

        return false;
    }
}