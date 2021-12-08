<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Violation;

interface ViolationReportInterface
{
    /**
     * @return string
     */
    public function getProject(): string;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Violation\PackageViolationReportInterface>
     */
    public function getPackages(): array;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Violation\ViolationInterface>
     */
    public function getViolations(): array;
}
