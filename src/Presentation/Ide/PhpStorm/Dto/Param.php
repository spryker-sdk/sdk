<?php
/*
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto;

class Param implements ParamInterface
{
    protected string $name;

    protected string|bool|int|float|array|null $defaultValue;

    /**
     * @param string $name
     * @param array|string|float|int|bool $defaultValue
     */
    public function __construct(string $name, float|int|bool|array|string|null $defaultValue)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|string|float|int|bool|null
     */
    public function getDefaultValue(): float|int|bool|array|string|null
    {
        return $this->defaultValue;
    }
}
