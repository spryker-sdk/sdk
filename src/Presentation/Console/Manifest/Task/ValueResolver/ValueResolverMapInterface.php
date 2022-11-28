<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Manifest\Task\ValueResolver;

use SprykerSdk\Sdk\Core\Application\Registry\RegistryItemInterface;

interface ValueResolverMapInterface extends RegistryItemInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array<mixed, \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig>
     */
    public function getMap(): array;
}
