<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Command;

use Sdk\Facade;
use Symfony\Component\Console\Command\Command;

class AbstractSdkConsole extends Command
{
    /**
     * @var \Sdk\Facade
     */
    protected $facade;

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
}
