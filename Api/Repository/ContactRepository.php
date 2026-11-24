<?php

namespace Scue\ESputnik\Api\Repository;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Validator\Exception;
use Scue\ESputnik\Api\Data\Contact;

interface ContactRepository
{
    /**
     * @api
     * @param SearchCriteriaInterface $searchCriteria
     * @return Contact[]
     */
    public function getList($searchCriteria);

    /**
     * @api
     * @param SearchCriteriaInterface $searchCriteria
     * @return Contact
     */
    public function findContact($searchCriteria): ?Contact;

    public function getByEmail(string $email): ?Contact;
    public function getByTelephone($telephone): ?Contact;
    public function getByExternalId($id): ?Contact;

    public function getSubscriptions(Contact $contact): ?array;
    public function isSubscribedToGroup(Contact $contact, $group): bool;
}