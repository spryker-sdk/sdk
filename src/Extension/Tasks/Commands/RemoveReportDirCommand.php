<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class RemoveReportDirCommand implements ExecutableCommandInterface
{
    /**
     * @var string
     */
    protected const NAME = 'remove:report:dir';

    /**
     * @uses \SprykerSdk\Sdk\Infrastructure\Repository\ViolationReportFileRepository
     *
     * @var string
     */
    protected const REPORT_DIR_SETTING_NAME = 'reportDir';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface
     */
    protected ViolationReportRepositoryInterface $violationReportRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface $violationReportRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     */
    public function __construct(
        ViolationReportRepositoryInterface $violationReportRepository,
        CliValueReceiver $cliValueReceiver
    ) {
        $this->violationReportRepository = $violationReportRepository;
        $this->cliValueReceiver = $cliValueReceiver;
    }

    /**
     * @param array $resolvedValues
     *
     * @return \SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface
     */
    public function execute(array $resolvedValues): CommandResponseInterface
    {
        $commandResponse = new CommandResponse(true);
        if (
            $this->cliValueReceiver->receiveValue(
                new ReceiverValue('Should report folder be cleaned?', true, 'boolean'),
            )
        ) {
            $this->violationReportRepository->cleanupViolationReport();
        }

        return $commandResponse;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return '';
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
}
