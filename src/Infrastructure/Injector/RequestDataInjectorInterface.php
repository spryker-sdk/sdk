<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Injector;

interface RequestDataInjectorInterface extends InjectorInterface
{
    /**
     * @param array $requestData
     *
     * @return void
     */
    public function setRequestData(array $requestData): void;
}
