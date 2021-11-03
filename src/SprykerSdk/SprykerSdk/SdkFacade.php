<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\SprykerSdk;


class SdkFacade implements SdkFacadeInterface
{
    /**
     * @var \SprykerSdk\Spryk\SprykFactory|null
     */
    protected $factory;

    /**
     * @codeCoverageIgnore
     *
     * @return \SprykerSdk\Spryk\SprykFactory
     */
    protected function getFactory(): SdkFactory
    {
        if ($this->factory === null) {
            $this->factory = new SdkFactory();
        }

        return $this->factory;
    }
}
