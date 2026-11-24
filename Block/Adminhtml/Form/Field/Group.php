<?php

namespace Scue\ESputnik\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Group extends AbstractFieldArray
{
    protected function _prepareToRender()
    {
        $this->addColumn('name', [
            'label' => __('Group name'),
            'class' => 'required-entry'
        ]);
        $this->addColumn('id', [
            'label' => __('Group id'),
            'class' => 'required-entry'
        ]);
    }
}