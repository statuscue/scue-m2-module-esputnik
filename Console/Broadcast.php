<?php

namespace Scue\ESputnik\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Scue\ESputnik\Model\Broadcast\EnumTemplate;
use Scue\ESputnik\Model\Broadcast\TemplateFactory;
use Scue\ESputnik\Service\Broadcast as Service;

class Broadcast extends Command
{
    public function __construct(
        private Service $service,
        private TemplateFactory $templateFactory,
        string $name = 'esputnik:broadcast:send'
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription(__('Broadcast message on channels'));

        foreach (BroadcastArguments::cases() as $argument) {
            $this->addArgument($argument->getName(), $argument->getMode(), $argument->getMode(), $argument->getDefault());
        }

        foreach (BroadcastOptions::cases() as $option) {
            $this->addOption($option->getName(), $option->getShortcut(), $option->getMode(), $option->getDescription(), $option->getDefault());
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($template = EnumTemplate::tryFrom($input->getArgument(BroadcastArguments::TEMPLATE->getName()))) {
            $template = $this->templateFactory->create($template->value, [
                'code' => $input->getOption(BroadcastOptions::CODE->getName())
            ]);
        } else {
            $template = $input->getArgument(BroadcastArguments::TEMPLATE->getName());
        }

        $params = [];

        foreach (BroadcastOptions::cases() as $option) {
            if (! $option->isIncludedInParams()) {
                continue;
            }

            $params[$option->getName()] = $input->getOption($option->getName());
        }

        $this->service->send(
            $input->getArgument(BroadcastArguments::CHANNEL->getName()),
            $input->getOption(BroadcastOptions::CONTACT->getName()),
            $template,
            $params
        );

        return 0;
    }
}