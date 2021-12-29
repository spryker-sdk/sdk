<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service;

interface CommandLoaderInterface
{
    /**
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface>
     */
    public function load(): array;
}
