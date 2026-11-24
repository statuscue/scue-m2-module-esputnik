<?php

namespace Scue\ESputnik\Block\Adminhtml\Form\Field\Event;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Magento\Sales\Model\Config\Source\Order\Status;
use Scue\ESputnik\Block\Adminhtml\Form\Field\Event;
use Scue\ESputnik\Block\Adminhtml\Form\Field\Renderer\Event\OrderStatusChanged as Renderer;

class OrderStatusChanged extends Event
{
    private $status;

    public function __construct(
        Status $status,
        Context $context, array
        $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        $this->status = $status;
        parent::__construct($context, $data, $secureRenderer);
    }

    protected function _prepareToRender()
    {
        $this->addColumn('order_status', [
            'label' => __('Order status'),
            'renderer' => $this->getRenderer('order_status', $this->status->toOptionArray(), Renderer::class)
        ]);
        $this->addColumn('esputnik_status', [
            'label' => __('ESputnik status'),
            'class' => 'required-entry'
        ]);
    }

    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $options = [];
        $status = $row->getData('order_status');

        if ($status !== null) {
            $options['option_' . $this->getRenderer('order_status')->calcOptionHash($status)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }
}