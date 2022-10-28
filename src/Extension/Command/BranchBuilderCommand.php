<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Command;

use Brancho\Command\BranchBuilderCommand as BranchoBranchBuilderCommand;
use SprykerSdk\Sdk\Infrastructure\Service\Filesystem;

class BranchBuilderCommand extends BranchoBranchBuilderCommand
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Filesystem $filesystem
     * @param string|null $name
     */
    public function __construct(Filesystem $filesystem, ?string $name = null)
    {
        $this->filesystem = $filesystem;
        parent::__construct($name);
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        defined('ROOT_DIR') || define('ROOT_DIR', $this->filesystem->getcwd());

        parent::configure();

        $this->setName('tool:git:branch');
    }
}
