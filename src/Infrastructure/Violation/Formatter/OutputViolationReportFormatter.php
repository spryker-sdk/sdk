<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Violation\Formatter;

use SprykerSdk\Sdk\Core\Application\Violation\ViolationReportFormatterInterface;
use SprykerSdk\Sdk\Infrastructure\Event\InputOutputReceiverInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class OutputViolationReportFormatter implements ViolationReportFormatterInterface, InputOutputReceiverInterface
{
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
     * @return string
     */
    public function getFormat(): string
    {
        return static::FORMAT;
    }

    /**
     * @param string $name
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function format(string $name, ViolationReportInterface $violationReport): void
    {
        if ($violationReport->getViolations()) {
            $table = new Table($this->output);
            $table
                ->setHeaderTitle('Violations found on project level')
                ->setHeaders(['Violation', 'Priority', 'Fixable']);
            foreach ($violationReport->getViolations() as $violation) {
                $table->addRow([$violation->getMessage(), $violation->priority(), $violation->isFixable() ? 'true' : 'false']);
            }
            $table->render();
        }

        if ($violationReport->getPackages()) {
            $packages = [];
            $violations = [];
            foreach ($violationReport->getPackages() as $package) {
                foreach ($package->getViolations() as $violation) {
                    $packages[] = [$violation->getId(), $violation->priority(), $violation->isFixable() ? 'true' : 'false', $package->getPackage()];
                }
                foreach ($package->getFileViolations() as $path => $fileViolations) {
                    foreach ($fileViolations as $fileViolation) {
                        $violations[] = [
                            $fileViolation->getId(),
                            $fileViolation->getMessage(),
                            $fileViolation->priority(),
                            $fileViolation->isFixable() ? 'true' : 'false',
                            $path,
                            $fileViolation->getStartLine(),
                            $fileViolation->getClass(),
                            $fileViolation->getMethod(),
                        ];
                    }
                }
            }

            if ($packages) {
                $table = new Table($this->output);
                $table
                    ->setHeaderTitle('Violations found on package level')
                    ->setHeaders(['Violation', 'Priority', 'Fixable', 'Package'])
                    ->setRows($packages);
                $table->render();
            }

            if ($violations) {
                $table = new Table($this->output);
                $table
                    ->setHeaderTitle('Violations found in files')
                    ->setHeaders(['Violation', 'Message', 'Priority', 'Fixable', 'File', 'Line', 'Class', 'Method'])
                    ->setRows($violations);
                $table->render();
            }
        }
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function read(string $name): ?ViolationReportInterface
    {
        return null;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return void
     */
    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
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
