<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Task\Command;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Extension\ValueResolver\PCSystemValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Process\Process;

class CheckMutagenCommand implements ExecutableCommandInterface, ErrorCommandInterface
{
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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        $resolvedValues = $context->getResolvedValues();
        $system = $resolvedValues['%' . PCSystemValueResolver::ALIAS . '%'] ?? null;

        if (in_array($system, [PCSystemValueResolver::MAC, PCSystemValueResolver::MAC_ARM])) {
            $process = Process::fromShellCommandline('mutagen version');
            $process->run();
            if ($process->getErrorOutput()) {
                $context->addMessage(static::class, new Message($process->getErrorOutput(), MessageInterface::DEBUG));
            }

            $context->setExitCode($process->getExitCode() ?? ContextInterface::SUCCESS_EXIT_CODE);
        }

        return $context;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return 'For using this task you should have Mutagen. You can find more details on https://docs.spryker.com/docs/scos/dev/setup/installing-spryker-with-docker/docker-installation-prerequisites/installing-docker-prerequisites-on-macos.html';
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
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
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
