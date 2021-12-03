<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks;

use SprykerSdk\Sdk\Contracts\Entity\ExecutableCommandInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;

class GreeterCommand implements ExecutableCommandInterface
{
    protected string $message;

    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return 'Greet' . $this->message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'php';
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return false;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    public function execute(Context $context): Context
    {
        $message = $this->message;

        $placeholders = array_map(function (mixed $placeholder): string {
            return '/' . preg_quote((string)$placeholder, '/') . '/';
        }, array_keys($context->getResolvedValues()));

        $values = array_map(function (mixed $value): string {
            return (string)$value;
        }, array_values($context->getResolvedValues()));

        $message = preg_replace($placeholders, $values, $message);

        if (!is_string($message)) {
            throw new CommandRunnerException(sprintf(
                'Could not assemble command %s with keys %s',
                $this->getCommand(),
                implode(', ', array_keys($values)),
            ));
        }


        $context->setResult(0);
        $context->addMessage(new Message($message, Message::SUCCESS));

        return $context;
    }
}
