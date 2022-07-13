<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractUpdateCommand extends Command
{
    /**
     * @var string
     */
    public const OPTION_CHECK_ONLY = 'check-only';

    /**
     * @var string
     */
    public const OPTION_NO_CHECK = 'no-check';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Update Spryker SDK to latest version.';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->addOption(
            static::OPTION_CHECK_ONLY,
            'c',
            InputOption::VALUE_OPTIONAL,
            'Update if the current version is\'n up-to-date.',
            false,
        );
        $this->addOption(
            static::OPTION_NO_CHECK,
            null,
            InputOption::VALUE_OPTIONAL,
            'Only checks if the current version is up-to-date',
            false,
        );
    }
}
