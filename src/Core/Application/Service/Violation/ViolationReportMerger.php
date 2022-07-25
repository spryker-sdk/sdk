<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\Violation;

use SprykerSdk\Sdk\Core\Application\Dto\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Application\Dto\Violation\ViolationReport;
use SprykerSdk\SdkContracts\Report\ReportMergerInterface;
use SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class ViolationReportMerger implements ReportMergerInterface
{
    /**
     * @param array<\SprykerSdk\SdkContracts\Violation\ViolationReportInterface> $violationReports
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface
     */
    public function merge(array $violationReports): ViolationReportInterface
    {
        $project = [
            'project' => '',
            'path' => '',
            'violations' => [],
        ];
        $packages = [];
        foreach ($violationReports as $violationReport) {
            $project['project'] = $violationReport->getProject();
            $project['path'] = $violationReport->getPath();
            foreach ($violationReport->getViolations() as $violation) {
                $project['violations'][] = $violation;
            }
            $packages = $this->mergePackages($packages, $violationReport->getPackages());
        }
        $packageViolationReports = [];
        foreach ($packages as $package) {
            $packageViolationReports[] = new PackageViolationReport($package['id'], $package['path'], $package['violations'], $package['files']);
        }

        return new ViolationReport($project['project'], $project['path'], $project['violations'], $packageViolationReports);
    }

    /**
     * @param array $packages
     * @param array $packageViolationReports
     *
     * @return array
     */
    protected function mergePackages(array $packages, array $packageViolationReports): array
    {
        foreach ($packageViolationReports as $packageViolationReport) {
            $packageData = $this->getArrayFromPackage($packageViolationReport);
            $packagePath = $packageViolationReport->getPath();

            if (!isset($packages[$packagePath])) {
                $packages[$packagePath] = $packageData;

                continue;
            }

            $packages[$packagePath]['violations'] = array_merge($packages[$packagePath]['violations'], $packageData['violations']);
            foreach ($packageData['files'] as $path => $violations) {
                if (!isset($packages[$packagePath]['files'][$path])) {
                    $packages[$packagePath]['files'][$path] = $violations;

                    continue;
                }
                $packages[$packagePath]['files'][$path] = array_merge($packages[$packagePath]['files'][$path], $violations);
            }
        }

        return $packages;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface $package
     *
     * @return array
     */
    protected function getArrayFromPackage(PackageViolationReportInterface $package): array
    {
        $packageData = [];
        $packageData['id'] = $package->getPath();
        $packageData['path'] = $package->getPath();
        $packageData['violations'] = $package->getViolations();
        $packageData['files'] = $package->getFileViolations();

        return $packageData;
    }
}
