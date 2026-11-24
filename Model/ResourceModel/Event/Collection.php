<?php

namespace Scue\ESputnik\Model\ResourceModel\Event;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Scue\ESputnik\Model\Event as Model;
use Scue\ESputnik\Model\ResourceModel\Event as ResourceModel;
use Scue\ESputnik\Model\ResourceModel\CollectionStatusFilterSupport;

class Collection extends AbstractCollection
{
    use CollectionStatusFilterSupport;

    protected $_eventPrefix = 'esputnik_event';
    protected $_eventObject = 'collection';

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}