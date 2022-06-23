<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository\Violation\Formatters;

use SprykerSdk\Sdk\Core\Appplication\Violation\ViolationReportFormatterInterface;
use SprykerSdk\Sdk\Infrastructure\Event\InputOutputReceiverInterface;
use SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OutputViolationReportFormatter implements ViolationReportFormatterInterface, InputOutputReceiverInterface
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected InputInterface $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface
     */
    protected ViolationReportFileMapperInterface $violationReportFileMapper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader
     */
    protected ViolationPathReader $violationPathReader;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\Violation\Formatters\HtmlViolationReportFormatter
     */
    protected HtmlViolationReportFormatter $htmlViolationReportFormatter;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface $violationReportFileMapper
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader $violationPathReader
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\Violation\Formatters\HtmlViolationReportFormatter $htmlViolationReportFormatter
     */
    public function __construct(
        ViolationReportFileMapperInterface $violationReportFileMapper,
        ViolationPathReader $violationPathReader,
        HtmlViolationReportFormatter $htmlViolationReportFormatter
    ) {
        $this->violationReportFileMapper = $violationReportFileMapper;
        $this->violationPathReader = $violationPathReader;
        $this->htmlViolationReportFormatter = $htmlViolationReportFormatter;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return 'output';
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

        $this->htmlViolationReportFormatter->format($name, $violationReport);

        if (file_exists($this->violationPathReader->getViolationReportPath($name, 'html'))) {
            $this->output->writeln(sprintf('<href=file:///%s>The HTML report (click here)</>', $this->violationPathReader->getViolationReportPath($name, 'html')));
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
