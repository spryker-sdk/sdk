<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Task\Command;

use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Enum\Task as EnumTask;

class CheckGitCommand implements CommandInterface, ErrorCommandInterface
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getCommand(): string
    {
        return 'git --version';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return 'For using this task you should to have GIT. More details you can find https://git-scm.com/book/en/v2/Getting-Started-Installing-Git';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return EnumTask::TASK_TYPE_LOCAL_CLI;
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
