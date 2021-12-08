<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Violation;

use SprykerSdk\Sdk\Contracts\Violation\PackageViolationReportInterface;
use SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;

class ViolationReportMerger
{
    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface> $violationReports
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface
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
            foreach ($violationReport->getPackages() as $package) {
                $packageData = $this->getArrayFromPackage($package);
                $packagePath = $package->getPath();
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
        }
        $packageEntities = [];
        foreach ($packages as $package) {
            $packageEntities[] = new PackageViolationReport($package['id'], $package['path'], $package['violations'], $package['files']);
        }

        return new ViolationReport($project['project'], $project['path'], $project['violations'], $packageEntities);
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Violation\PackageViolationReportInterface $package
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
