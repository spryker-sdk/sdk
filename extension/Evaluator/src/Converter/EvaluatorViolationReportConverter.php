<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Evaluator\Converter;

use SprykerSdk\Sdk\Core\Application\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Application\Dto\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Application\Violation\AbstractViolationConverter;
use SprykerSdk\SdkContracts\Enum\Setting;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

class EvaluatorViolationReportConverter extends AbstractViolationConverter
{
    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @var string
     */
    protected string $producer;

    /**
     * {@inheritDoc}
     *
     * @param array $configuration
     *
     * @return void
     */
    public function configure(array $configuration): void
    {
        $this->fileName = $configuration['input_file'];
        $this->producer = $configuration['producer'];
    }

    /**
     * {@inheritDoc}
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface|null
     */
    public function convert(): ?ViolationReportInterface
    {
        $projectDirectory = $this->settingRepository->findOneByPath(Setting::PATH_PROJECT_DIR);

        if (!$projectDirectory) {
            return null;
        }
        $jsonReport = $this->readFile();

        if (!$jsonReport) {
            return null;
        }
        $report = json_decode($jsonReport, true);

        if (!$report && !is_array($report)) {
            return null;
        }

        $violations = [];
        foreach ($report as $checker => $checkerReport) {
            if (empty($checkerReport['violations'])) {
                continue;
            }

            foreach ($checkerReport['violations'] as $violation) {
                $reportViolation = new Violation($checker, sprintf("Checker: %s\nMessage: %s\nTarget: %s\n%s", $checker, $violation['message'], $violation['target'], $checkerReport['docUrl']));
                $reportViolation->setClass($violation['target']);
                $reportViolation->setFixable(true);

                $violations[] = $reportViolation;
            }
        }

        /** @var \SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface $projectDirectory */
        $projectDirectory = $this->settingRepository->findOneByPath(Setting::PATH_PROJECT_DIR);

        return new ViolationReport(
            basename($projectDirectory->getValues()),
            '.' . DIRECTORY_SEPARATOR,
            $violations,
            [],
        );
    }
}
