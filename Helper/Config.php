<?php

namespace Scue\ESputnik\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper
{
    const SECTION = 'esputnik';

    public function getConfig(string $group, string $field, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->scopeConfig->getValue(
            sprintf('%s/%s/%s', self::SECTION, $group, $field),
            $scopeType,
            $scopeCode
        );
    }

    public function getGeneralConfig(string $field, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->getConfig('general', $field, $scopeType, $scopeCode);
    }

    public function getLogin($scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->getGeneralConfig('login', $scopeType, $scopeCode);
    }

    public function getPassword($scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->getGeneralConfig('password', $scopeType, $scopeCode);
    }

    public function getCleanInterval($scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->getGeneralConfig('clean_interval', $scopeType, $scopeCode);
    }

    public function getGroupsConfig(string $field, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->getConfig('groups', $field, $scopeType, $scopeCode);
    }

    public function getAvailableSubscriptionGroups($scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null): array
    {
        if ($groups = $this->getGroupsConfig('available', $scopeType, $scopeCode)) {
            $groups = json_decode($groups, true);
            $values = array_column($groups, 'id');
            $names = array_column($groups, 'name');

            return array_combine($values, $names);
        }

        return [];
    }

    public function getAvailableSubscriptionGroupIds($scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null): array
    {
        return array_keys($this->getAvailableSubscriptionGroups($scopeType, $scopeCode));
    }

    public function getAvailableSubscriptionGroupNames($scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null): array
    {
        return array_values($this->getAvailableSubscriptionGroups($scopeType, $scopeCode));
    }

    public function getSubscriptionGroupName($id, $default = null, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null): ?string
    {
        $groups = $this->getAvailableSubscriptionGroups($scopeType, $scopeCode);
        return $groups[$id] ?? $default;
    }

    public function getEventConfig(string $field, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->getConfig('events', $field, $scopeType, $scopeCode);
    }

    public function getEventStatusByOrderStatus(string $status, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null): ?string
    {
        if ($statuses = $this->getEventConfig('order_status_changed', $scopeType, $scopeCode)) {
            $statuses = json_decode($statuses, true);

            foreach ($statuses as $config) {
                if ($config['order_status'] != $status) {
                    continue;
                }

                return $config['esputnik_status'];
            }
        }

        return null;
    }

    public function getBroadcastConfig(string $field, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->getConfig('broadcast', $field, $scopeType, $scopeCode);
    }
}