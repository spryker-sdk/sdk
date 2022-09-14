<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Task;

use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Extension\Task\Command\RemoveReportDirCommand;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class RemoveRepDirTask implements TaskInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface
     */
    protected ViolationReportRepositoryInterface $violationReportRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface $violationReportRepository
     */
    public function __construct(
        ViolationReportRepositoryInterface $violationReportRepository
    ) {
        $this->violationReportRepository = $violationReportRepository;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getShortDescription(): string
    {
        return 'This command cleanup report directory';
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getPlaceholders(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'violation:php:clean-report-dir';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return [
            new RemoveReportDirCommand($this->violationReportRepository),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getVersion(): string
    {
        return '0.1.0';
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getSuccessor(): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isOptional(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface
     */
    public function getLifecycle(): LifecycleInterface
    {
        return new Lifecycle(
            new InitializedEventData(),
            new UpdatedEventData(),
            new RemovedEventData(),
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getStages(): array
    {
        return [];
    }
}
