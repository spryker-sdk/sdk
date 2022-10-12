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

class CheckDockerComposeCommand implements CommandInterface, ErrorCommandInterface
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getCommand(): string
    {
        return 'docker-compose -v';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return 'For using this task you should have docker-compose. You can find more details on https://docs.spryker.com/docs/scos/dev/setup/installing-spryker-with-docker/installing-spryker-with-docker.html#prerequisites';
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
