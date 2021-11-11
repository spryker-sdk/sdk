<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Command;

use Sdk\Facade;
use Sdk\Factory;
use Symfony\Component\Console\Command\Command;

class AbstractSdkConsole extends Command
{
    /**
     * @var \Sdk\Facade
     */
    protected $facade;

    /**
     * @var \Sdk\Factory
     */
    protected $factory;

    /**
     * @return \Sdk\Facade
     */
    public function getFacade(): Facade
    {
        if (!$this->facade) {
            $this->facade = new Facade();
        }

        return $this->facade;
    }

    /**
     * @return \Sdk\Facade
     */
    public function getFactory(): Factory
    {
        if (!$this->factory) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }
}
