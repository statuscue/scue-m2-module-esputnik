<?php

namespace Scue\ESputnik\Model;

use Magento\Framework\DataObject;
use Scue\ESputnik\Api\Data\Contact as IContact;

class Contact extends DataObject implements IContact
{
    public function getContactId()
    {
        return $this->getData('id');
    }
}