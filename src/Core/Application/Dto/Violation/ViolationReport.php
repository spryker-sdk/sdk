<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Violation;

use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

class ViolationReport implements ViolationReportInterface
{
    /**
     * @var string
     */
    protected string $project;

    /**
     * @var array<\SprykerSdk\SdkContracts\Report\Violation\PackageViolationReportInterface>
     */
    protected array $packages;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface>
     */
    protected array $violations;

    /**
     * @param string $project
     * @param string $path
     * @param array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface> $violations
     * @param array<\SprykerSdk\SdkContracts\Report\Violation\PackageViolationReportInterface> $packages
     */
    public function __construct(string $project, string $path, array $violations = [], array $packages = [])
    {
        $this->project = $project;
        $this->path = $path;
        $this->violations = $violations;
        $this->packages = $packages;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getProject(): string
    {
        return $this->project;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\SprykerSdk\SdkContracts\Report\Violation\PackageViolationReportInterface>
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
