<?php

namespace Scue\ESputnik\Model\ResourceModel;

trait ScalarDataSanitizer
{
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        foreach ($object->getData() as $key => $value) {
            if (! is_null($value) && ! is_scalar($value)) {
                $object->setData($key, json_encode($value));
            }
        }

        return parent::_beforeSave($object);
    }
}