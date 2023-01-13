<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerSdk\Sdk\Infrastructure\Violation\Formatter;

use SprykerSdk\Sdk\Core\Application\Dto\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Application\Dto\Violation\ViolationReport;
use SprykerSdk\SdkContracts\Report\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

class ViolationReportDecorator
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Violation\Formatter\OutputViolationDecoratorInterface>
     */
    protected iterable $violationDecorators;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Violation\Formatter\OutputViolationDecoratorInterface> $violationDecorators
     */
    public function __construct(iterable $violationDecorators)
    {
        $this->violationDecorators = $violationDecorators;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface $violationReport
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface
     */
    public function decorate(ViolationReportInterface $violationReport): ViolationReportInterface
    {
        $violations = [];

        foreach ($violationReport->getViolations() as $violation) {
            $violations[] = $this->decorateViolation($violation);
        }

        $packages = [];

        foreach ($violationReport->getPackages() as $package) {
            $packagesViolations = [];
            foreach ($package->getViolations() as $violation) {
                $packagesViolations[] = $this->decorateViolation($violation);
            }

            $packageFileViolations = [];
            foreach ($package->getFileViolations() as $path => $fileViolations) {
                $packageFileSubViolations = [];

                foreach ($fileViolations as $fileViolation) {
                    $packageFileSubViolations[] = $this->decorateViolation($fileViolation);
                }

                $packageFileViolations[$path] = $packageFileSubViolations;
            }

            $packages[] = new PackageViolationReport($package->getPackage(), $package->getPath(), $packagesViolations, $packageFileViolations);
        }

        return new ViolationReport(
            $violationReport->getProject(),
            $violationReport->getPath(),
            $violations,
            $packages,
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Report\Violation\ViolationInterface $violation
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationInterface
     */
    protected function decorateViolation(ViolationInterface $violation): ViolationInterface
    {
        foreach ($this->violationDecorators as $violationDecorator) {
            $violation = $violationDecorator->decorate($violation);
        }

        return $violation;
    }
}
