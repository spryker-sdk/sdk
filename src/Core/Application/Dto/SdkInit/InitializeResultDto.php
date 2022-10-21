<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\SdkInit;

class InitializeResultDto
{
    /**
     * @var bool
     */
    protected bool $isSuccessful = true;

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    /**
     * @return void
     */
    public function success(): void
    {
        $this->isSuccessful = true;
    }

    /**
     * @return void
     */
    public function fail()
    {
        $this->isSuccessful = false;
    }
}
