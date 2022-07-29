<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\SdkContracts\Report\ReportConverterInterface;

interface ConverterRegistryInterface
{
    /**
     * @param string $class
     *
     * @return bool
     */
    public function has(string $class): bool;

    /**
     * @param string $class
     *
     * @return \SprykerSdk\SdkContracts\Report\ReportConverterInterface|null
     */
    public function get(string $class): ?ReportConverterInterface;
}
