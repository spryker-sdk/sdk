<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

use Symfony\Component\Console\Output\OutputInterface;

interface ExecutableCommandInterface extends CommandInterface
{
    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $resolvedValues
     *
     * @return int
     */
    public function execute(OutputInterface $output, array $resolvedValues): int;
}
