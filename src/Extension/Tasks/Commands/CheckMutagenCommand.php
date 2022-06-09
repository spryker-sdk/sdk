<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Extension\ValueResolvers\PCSystemValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Process\Process;

class CheckMutagenCommand implements ExecutableCommandInterface, ErrorCommandInterface
{
    /**
     * @return string
     */
    public function getCommand(): string
    {
        return '';
    }

    /**
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
            $context->addMessage(static::class, new Message($process->getErrorOutput(), MessageInterface::DEBUG));

            $context->setExitCode($process->getExitCode() ?? ContextInterface::SUCCESS_EXIT_CODE);
        }

        return $context;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return 'For using this task you should to have mutagen. More details you can find https://docs.spryker.com/docs/scos/dev/setup/installing-spryker-with-docker/docker-installation-prerequisites/installing-docker-prerequisites-on-macos.html';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'php';
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function getViolationConverter(): ?ConverterInterface
    {
        return null;
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return ContextInterface::DEFAULT_STAGE;
    }
}
