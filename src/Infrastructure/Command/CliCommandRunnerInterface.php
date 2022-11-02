<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Command;

use SprykerSdk\Sdk\Core\Application\Dependency\CommandRunnerInterface;
use SprykerSdk\Sdk\Infrastructure\Event\InputOutputReceiverInterface;
use Symfony\Component\Console\Helper\HelperSet;

interface CliCommandRunnerInterface extends CommandRunnerInterface, InputOutputReceiverInterface
{
    /**
     * @param \Symfony\Component\Console\Helper\HelperSet $helperSet
     *
     * @return void
     */
    public function setHelperSet(HelperSet $helperSet);
}
