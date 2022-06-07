<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

interface LifecycleManagerInterface
{
    /**
     * @return void
     */
    public function update(): void;

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\MessageInterface>
     */
    public function checkForUpdate(): array;
}
