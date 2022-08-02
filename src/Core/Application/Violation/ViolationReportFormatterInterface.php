<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Violation;

use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

interface ViolationReportFormatterInterface
{
    /**
     * @return string
     */
    public function getFormat(): string;

    /**
     * @param string $name
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function format(string $name, ViolationReportInterface $violationReport): void;

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function read(string $name): ?ViolationReportInterface;
}
