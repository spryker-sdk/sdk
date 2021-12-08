<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Violation;

use SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface;

class ViolationReport implements ViolationReportInterface
{
    /**
     * @var string
     */
    protected string $project;

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Violation\PackageViolationReportInterface>
     */
    protected array $packages;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Violation\ViolationInterface>
     */
    protected array $violations;

    /**
     * @param string $project
     * @param string $path
     * @param array<\SprykerSdk\Sdk\Contracts\Violation\ViolationInterface> $violations
     * @param array<\SprykerSdk\Sdk\Contracts\Violation\PackageViolationReportInterface> $packages
     */
    public function __construct(string $project, string $path, array $violations, array $packages)
    {
        $this->project = $project;
        $this->path = $path;
        $this->violations = $violations;
        $this->packages = $packages;
    }

    /**
     * @return string
     */
    public function getProject(): string
    {
        return $this->project;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Violation\PackageViolationReportInterface>
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Violation\ViolationInterface>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
