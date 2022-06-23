<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto;

interface ParamInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array|string|float|int|bool|null
     */
    public function getDefaultValue();
}
