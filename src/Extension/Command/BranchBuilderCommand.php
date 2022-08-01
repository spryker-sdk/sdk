<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Command;

use Brancho\Command\BranchBuilderCommand as BranchoBranchBuilderCommand;

class BranchBuilderCommand extends BranchoBranchBuilderCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        defined('ROOT_DIR') || define('ROOT_DIR', getcwd());

        parent::configure();

        $this->setName('tool:git:branch');
    }
}
