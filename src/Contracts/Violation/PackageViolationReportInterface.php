<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Violation;

interface PackageViolationReportInterface
{
    /**
     * @return string
     */
    public function getPackage(): string;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Violation\ViolationInterface>
     */
    public function getViolations(): array;

    /**
     * @return array<string, array<\SprykerSdk\Sdk\Contracts\Violation\ViolationInterface>>
     */
    public function getFileViolations(): array;
}
