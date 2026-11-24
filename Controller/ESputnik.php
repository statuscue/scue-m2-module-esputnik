<?php

namespace Scue\ESputnik\Controller;

use Exception;
use Magento\Framework\Validator\Exception as ValidatorException;
use Magento\Framework\Message\MessageInterface;
use Magento\Framework\App\ActionInterface;

abstract class ESputnik implements ActionInterface
{
    protected $context;

    protected $statuseTypes = [
        MessageInterface::TYPE_SUCCESS,
        MessageInterface::TYPE_ERROR,
        MessageInterface::TYPE_NOTICE,
        MessageInterface::TYPE_WARNING
    ];

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    protected function process(callable $callback, string $successMessage, string $errorMessage)
    {
        try {
            $callback();
            $this->context->getMessageManager()->addSuccessMessage($successMessage);
        } catch (ValidatorException $exception) {
            array_walk($this->statuseTypes, function($type) use ($exception) {
                if ($typeMessages = $exception->getMessages($type)) {
                    $methodName = sprintf('add%sMessage', ucfirst(strtolower($type)));

                    array_walk($typeMessages, function(MessageInterface $message) use ($methodName) {
                        $this->context->getMessageManager()->$methodName($message->getText());
                    });
                }
            });
        } catch (Exception $exception) {
            $this->context->getMessageManager()->addExceptionMessage($exception, $errorMessage);
        }
    }
}