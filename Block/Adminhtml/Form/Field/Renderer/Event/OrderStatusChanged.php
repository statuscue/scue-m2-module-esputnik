<?php

namespace Scue\ESputnik\Block\Adminhtml\Form\Field\Renderer\Event;

use Magento\Framework\View\Element\Html\Select;

class OrderStatusChanged extends Select
{
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    public function setInputId($value)
    {
        return $this->setId($value);
    }
}