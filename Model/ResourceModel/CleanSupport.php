<?php

namespace Scue\ESputnik\Model\ResourceModel;

use Zend_Db_Expr;

trait CleanSupport
{
    public function clean(int $days)
    {
        $this->getConnection()->delete(
            $this->getMainTable(),
            new Zend_Db_Expr('`created_at` < NOW() - INTERVAL ' . $days . ' DAY')
        );
    }
}