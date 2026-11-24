<?php

namespace Scue\ESputnik\Model;

use Magento\Framework\Model\AbstractModel;
use Scue\ESputnik\Api\Data\Broadcast;

class Message extends AbstractModel implements Broadcast
{
    protected $_eventPrefix = 'esputnik_message';
    protected $_eventObject = 'message';

    protected function _construct()
    {
        $this->_init(ResourceModel\Message::class);
    }

    public function getChannel(): string
    {
        return $this->getData('channel');
    }

    public function getContact(): array
    {
        $contact = $this->getData('contact');
        $contact = json_decode($contact, true);

        return $contact;
    }

    public function getMessage(): string
    {
        return $this->getData('message');
    }

    public function getParams(): array
    {
        $params = $this->getData('params');
        $params = json_decode($params, true);

        return $params;
    }
}