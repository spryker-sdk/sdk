<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface;

class ReceivedValue implements InteractionValueConfig
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface
     */
    protected ReceiverValueInterface $value;

    /**
     * @var bool
     */
    protected bool $required;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface $value
     * @param bool $required
     */
    public function __construct(ReceiverValueInterface $value, bool $required = true)
    {
        $this->value = $value;
        $this->required = $required;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface
     */
    public function getValue(): ReceiverValueInterface
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
}
