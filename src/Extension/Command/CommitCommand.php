<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Command;

use Brancho\Command\CommitCommand as BranchoCommitCommand;
use SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem;

class CommitCommand extends BranchoCommitCommand
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem $filesystem
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

        $this->setName('tool:git:commit');
    }
}
