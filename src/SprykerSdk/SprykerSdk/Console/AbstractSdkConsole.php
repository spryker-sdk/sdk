<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\SprykerSdk\Console;

use SprykerSdk\SprykerSdk\SdkConfig;
use SprykerSdk\SprykerSdk\SdkFacade;
use SprykerSdk\SprykerSdk\SdkFacadeInterface;
use SprykerSdk\SprykerSdk\SdkFactory;
use Symfony\Component\Console\Command\Command;

abstract class AbstractSdkConsole extends Command
{
    /**
     * @var int
     */
    protected const CODE_SUCCESS = 0;
    /**
     * @var int
     */
    protected const CODE_ERROR = 1;

    /**
     * @var \SprykerSdk\SprykerSdk\SdkFacadeInterface|null
     */
    protected $facade;

    /**
     * @var \SprykerSdk\SprykerSdk\SdkFactory|null
     */
    protected $factory;

    /**
     * @var \SprykerSdk\SprykerSdk\SdkConfig|null
     */
    protected $config;

    /**
     * @return \SprykerSdk\SprykerSdk\SdkFacadeInterface
     */
    protected function getFacade(): SdkFacadeInterface
    {
        if ($this->facade === null) {
            $this->facade = new SdkFacade();
        }

        return $this->facade;
    }

    /**
     * @return \SprykerSdk\SprykerSdk\SdkFactory
     */
    protected function getFactory(): SdkFactory
    {
        if ($this->factory === null) {
            $this->factory = new SdkFactory();
        }

        return $this->factory;
    }

    /**
     * @return \SprykerSdk\SprykerSdk\ConfigFactory
     */
    protected function getConfig(): SdkFactory
    {
        if ($this->factory === null) {
            $this->factory = new SdkConfig();
        }

        return $this->factory;
    }
}
