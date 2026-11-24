<?php

namespace Scue\ESputnik\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Message extends AbstractDb
{
    use CleanSupport, ScalarDataSanitizer;

    protected function _construct()
    {
        $this->_init('scue_esputnik_message', 'id');
    }
}