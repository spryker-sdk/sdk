<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config;

class CallbackValue implements InteractionValueConfig
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var bool
     */
    protected bool $required;

    /**
     * @param callable $callback
     * @param bool $required
     */
    public function __construct(callable $callback, bool $required = true)
    {
        $this->callback = $callback;
        $this->required = $required;
    }

    /**
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
}
