<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

interface ConfigurableValueResolverInterface extends ValueResolverInterface
{
    /**
     * @param array $values
     */
    public function configure(array $values): void;
}