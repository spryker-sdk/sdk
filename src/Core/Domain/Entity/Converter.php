<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\Sdk\Contracts\Entity\ConverterInterface;

class Converter implements ConverterInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array
     */
    protected array $configuration;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
