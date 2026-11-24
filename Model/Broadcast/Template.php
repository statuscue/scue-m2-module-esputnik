<?php

namespace Scue\ESputnik\Model\Broadcast;

use Scue\ESputnik\Api\Template as ITemplate;
use Magento\Framework\Filter\Template as BaseTemplate;
use Magento\Framework\Filter\Template\FilteringDepthMeter;
use Magento\Framework\Filter\Template\SignatureProvider;
use Magento\Framework\Filter\VariableResolverInterface;
use Magento\Framework\Stdlib\StringUtils;
use Scue\ESputnik\Helper\Config\Template as Config;

class Template extends BaseTemplate implements ITemplate
{
    public function __construct(
        private Config $config,
        StringUtils $string,
        $variables = [],
        $directiveProcessors = [],
        VariableResolverInterface $variableResolver = null,
        SignatureProvider $signatureProvider = null,
        FilteringDepthMeter $filteringDepthMeter = null
    ) {
        parent::__construct($string, $variables, $directiveProcessors, $variableResolver, $signatureProvider, $filteringDepthMeter);
    }

    public function getTemplate(): string
    {
        return $this->config->getTemplate();
    }

    public function __toString(): string
    {
        return $this->filter($this->getTemplate());
    }
}