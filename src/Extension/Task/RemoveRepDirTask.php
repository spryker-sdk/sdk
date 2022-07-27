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
     * @uses \SprykerSdk\Sdk\Infrastructure\Repository\ViolationReportFileRepository
     *
     * @var string
     */
    protected const REPORT_DIR_SETTING_NAME = 'reportDir';

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
     * @return string
     */
    public function getShortDescription(): string
    {
        return 'This command cleanup report directory';
    }

    /**
     * @return array
     */
    public function getPlaceholders(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'violation:php:clean-report-dir';
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return [
            new RemoveReportDirCommand($this->violationReportRepository),
        ];
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return '0.1.0';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }

    /**
     * @return string|null
     */
    public function getSuccessor(): ?string
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return true;
    }

    /**
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
     * @return array<string>
     */
    public function getStages(): array
    {
        return [];
    }
}
