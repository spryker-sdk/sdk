<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\TaskInit;

class BeforeInitDto
{
    /**
     * @var string
     */
    protected string $callSource = '';

    /**
     * @param string $callSource
     */
    public function __construct(string $callSource)
    {
        $this->callSource = $callSource;
    }

    /**
     * @return string
     */
    public function getCallSource(): string
    {
        return $this->callSource;
    }
}
