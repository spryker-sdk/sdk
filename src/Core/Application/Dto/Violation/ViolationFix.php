<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Violation;

use SprykerSdk\SdkContracts\Report\Violation\ViolationFixInterface;

class ViolationFix implements ViolationFixInterface
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $action;

    /**
     * @param string $type
     * @param string $action
     */
    public function __construct(string $type, string $action)
    {
        $this->type = $type;
        $this->action = $action;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
