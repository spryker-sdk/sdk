<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Hello\Task\Command;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class GreeterCommand implements ExecutableCommandInterface
{
    protected string $message;

    /**
     * {@inheritDoc}
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getCommand(): string
    {
        return static::class;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return 'php';
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getTags(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
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

        $context->setExitCode(0);
        $context->addMessage($this->getCommand(), new Message($message, MessageInterface::SUCCESS));

        return $context;
    }

    /**
     * {@inheritDoc}
     *
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function getConverter(): ?ConverterInterface
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getStage(): string
    {
        return ContextInterface::DEFAULT_STAGE;
    }
}
