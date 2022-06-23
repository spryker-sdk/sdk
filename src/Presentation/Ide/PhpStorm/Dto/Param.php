<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto;

class Param implements ParamInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array|string|float|int|bool|null
     */
    protected $defaultValue;

    /**
     * @param string $name
     * @param array|string|float|int|bool|null $defaultValue
     */
    public function __construct(string $name, $defaultValue)
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
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
