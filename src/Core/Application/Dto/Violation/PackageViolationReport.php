<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Violation;

use SprykerSdk\SdkContracts\Report\Violation\PackageViolationReportInterface;

class PackageViolationReport implements PackageViolationReportInterface
{
    /**
     * @var string
     */
    protected string $package;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface>
     */
    protected array $violations;

    /**
     * @var array<string, array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface>>
     */
    protected array $fileViolations;

    /**
     * @param string $package
     * @param string $path
     * @param array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface> $violations
     * @param array<string, array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface>> $fileViolations
     */
    public function __construct(string $package, string $path, array $violations = [], array $fileViolations = [])
    {
        $this->package = $package;
        $this->path = $path;
        $this->violations = $violations;
        $this->fileViolations = $fileViolations;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getPackage(): string
    {
        return $this->package;
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

    /**
     * {@inheritDoc}
     *
     * @return array<string, array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface>>
     */
    public function getFileViolations(): array
    {
        return $this->fileViolations;
    }
}
