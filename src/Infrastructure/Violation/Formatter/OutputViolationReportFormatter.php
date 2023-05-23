<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Violation\Formatter;

use SprykerSdk\Sdk\Core\Application\Violation\ViolationReportFormatterInterface;
use SprykerSdk\Sdk\Infrastructure\Injector\OutputInjectorInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class OutputViolationReportFormatter implements ViolationReportFormatterInterface, OutputInjectorInterface
{
    /**
     * @var string
     */
    public const FALLBACK_VALUE_NOT_AVAILABLE = 'n/a';

    /**
     * @var string
     */
    public const FORMAT = 'output';

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected InputInterface $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Violation\Formatter\ViolationReportDecorator
     */
    protected ViolationReportDecorator $violationReportDecorator;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Violation\Formatter\ViolationReportDecorator $violationReportDecorator
     */
    public function __construct(ViolationReportDecorator $violationReportDecorator)
    {
        $this->violationReportDecorator = $violationReportDecorator;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return static::FORMAT;
    }

    /**
     * @param string $name
     * @param \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function format(string $name, ViolationReportInterface $violationReport): void
    {
        $violationReport = $this->violationReportDecorator->decorate($violationReport);
        $tableSeparator = new TableSeparator();
        if ($violationReport->getViolations()) {
            $table = new Table($this->output);
            $table
                ->setHeaderTitle('Violations found on project level')
                ->setHeaders(['Violation', 'Fixable']);
            $projectViolations = [];
            foreach ($violationReport->getViolations() as $violation) {
                $projectViolations[] = [$violation->getMessage(), $violation->isFixable() ? 'true' : 'false'];
                $projectViolations[] = $tableSeparator;
            }
            array_pop($projectViolations);

            $table->setRows($projectViolations);
            $table->render();
        }

        if ($violationReport->getPackages()) {
            $packages = [];
            $violations = [];
            foreach ($violationReport->getPackages() as $package) {
                foreach ($package->getViolations() as $violation) {
                    $packages[] = [$violation->getId(), $violation->isFixable() ? 'true' : 'false', $package->getPackage()];
                    $packages[] = $tableSeparator;
                }

                foreach ($package->getFileViolations() as $path => $fileViolations) {
                    foreach ($fileViolations as $fileViolation) {
                        $violations[] = [
                            $fileViolation->getId(),
                            $fileViolation->getMessage() ?: static::FALLBACK_VALUE_NOT_AVAILABLE,
                            $fileViolation->isFixable() ? 'true' : 'false',
                            $path,
                            $fileViolation->getStartLine() ?: static::FALLBACK_VALUE_NOT_AVAILABLE,
                            $fileViolation->getClass() ?: static::FALLBACK_VALUE_NOT_AVAILABLE,
                            $fileViolation->getMethod() ?: static::FALLBACK_VALUE_NOT_AVAILABLE,
                        ];

                        $violations[] = new TableSeparator();
                    }
                }
            }

            if ($packages) {
                array_pop($packages);
                $table = new Table($this->output);
                $table
                    ->setHeaderTitle('Violations found on package level')
                    ->setHeaders(['Violation', 'Fixable', 'Package'])
                    ->setRows($packages);
                $table->render();
            }

            if ($violations) {
                array_pop($violations);
                $table = new Table($this->output);
                $table
                    ->setHeaderTitle('Violations found in files')
                    ->setHeaders(['Violation', 'Message', 'Fixable', 'File', 'Line', 'Class', 'Method'])
                    ->setRows($violations);
                $table->render();
            }
        }
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface|null
     */
    public function read(string $name): ?ViolationReportInterface
    {
        return null;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }
}
