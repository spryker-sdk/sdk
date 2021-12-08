<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveReportDirCommand extends Command
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

        parent::__construct(static::NAME);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        if (
            $this->cliValueReceiver->receiveValue(
                new ReceiverValue('Should report folder be removed?', true, 'boolean'),
            )
        ) {
            $this->violationReportRepository->removeViolationReport();
        }

        return static::SUCCESS;
    }
}
