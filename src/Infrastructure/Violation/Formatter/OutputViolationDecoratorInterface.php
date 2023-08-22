<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Violation\Formatter;

use SprykerSdk\SdkContracts\Report\Violation\ViolationInterface;

interface OutputViolationDecoratorInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Report\Violation\ViolationInterface $violation
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationInterface
     */
    public function decorate(ViolationInterface $violation): ViolationInterface;
}
