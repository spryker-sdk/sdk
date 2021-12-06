<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;

interface CommandExecutorInterface
{
    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface> $commands
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $placeholders
     * @param callable|null $afterCommandExecutedCallback
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse
     */
    public function execute(array $commands, array $placeholders, ?callable $afterCommandExecutedCallback = null): CommandResponse;
}
