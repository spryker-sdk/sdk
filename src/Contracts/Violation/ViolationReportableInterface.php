<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Violation;

interface ViolationReportableInterface
{
    /**
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface|null
     */
    public function getViolationReport(): ?ViolationReportInterface;
}
