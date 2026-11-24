<?php

namespace Scue\ESputnik\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\View\Element\Html\Select;
use Magento\Framework\View\Element\AbstractBlock;

abstract class Event extends AbstractFieldArray
{
    private $renders = [];

    protected function getRenderer(string $name, array $options = [], string $block = Select::class): AbstractBlock
    {
        if (! isset($this->renders[$name])) {
            $this->renders[$name] = $this->getLayout()->createBlock(
                $block,
                '',
                [
                    'data' => [
                        'is_render_to_js_template' => true
                    ]
                ]
            )->setOptions($options);
        }

        return $this->renders[$name];
    }
}