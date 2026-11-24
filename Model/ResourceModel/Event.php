<?php

namespace Scue\ESputnik\Model\ResourceModel;

use Zend_Db_Expr;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Event extends AbstractDb
{
    use CleanSupport, ScalarDataSanitizer;

    protected function _construct()
    {
        $this->_init('scue_esputnik_event', 'id');
    }
}