<?php

namespace Scue\ESputnik\Model\ResourceModel\Message;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Scue\ESputnik\Model\Message as Model;
use Scue\ESputnik\Model\ResourceModel\Message as ResourceModel;
use Scue\ESputnik\Model\ResourceModel\CollectionStatusFilterSupport;

class Collection extends AbstractCollection
{
    use CollectionStatusFilterSupport;

    protected $_eventPrefix = 'esputnik_message';
    protected $_eventObject = 'collection';

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    public function addChannelFilter(...$channels): self
    {
        return $this->addFieldToFilter('channel', ['in' => $channels]);
    }
}