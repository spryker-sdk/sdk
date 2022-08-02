<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Command;

use Brancho\Command\CommitCommand as BranchoCommitCommand;

class CommitCommand extends BranchoCommitCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        defined('ROOT_DIR') || define('ROOT_DIR', getcwd());

        parent::configure();

        $this->setName('tool:git:commit');
    }
}
