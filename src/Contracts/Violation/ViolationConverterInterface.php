<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Violation;

interface ViolationConverterInterface
{
    /**
     * @param array $configuration
     *
     * @return void
     */
    public function configure(array $configuration): void;

    /**
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface|null
     */
    public function convert(): ?ViolationReportInterface;
}
