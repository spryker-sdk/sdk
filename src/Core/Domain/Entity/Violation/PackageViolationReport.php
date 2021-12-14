<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Violation;

use SprykerSdk\Sdk\Contracts\Violation\PackageViolationReportInterface;

class PackageViolationReport implements PackageViolationReportInterface
{
    protected string $package;

    protected string $path;

    protected array $violations;

    protected array $fileViolations;

    /**
     * @param string $package
     * @param string $path
     * @param array<\SprykerSdk\Sdk\Contracts\Violation\ViolationInterface> $violations
     * @param array<string, array<\SprykerSdk\Sdk\Contracts\Violation\ViolationInterface>> $fileViolations
     */
    public function __construct(string $package, string $path, array $violations, array $fileViolations)
    {
        $this->package = $package;
        $this->path = $path;
        $this->violations = $violations;
        $this->fileViolations = $fileViolations;
    }

    /**
     * @return string
     */
    public function getPackage(): string
    {
        return $this->package;
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

    /**
     * @return array<string, array<\SprykerSdk\Sdk\Contracts\Violation\ViolationInterface>>
     */
    public function getFileViolations(): array
    {
        return $this->fileViolations;
    }
}
