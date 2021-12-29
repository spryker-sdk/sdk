<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dto;

use SprykerSdk\SdkContracts\ValueReceiver\ReceiverValueInterface;

class ReceiverValue implements ReceiverValueInterface
{
    protected string $description;

    protected mixed $defaultValue;

    protected string $type;

    protected array $choiceValues;

    /**
     * @param string $description
     * @param mixed $defaultValue
     * @param string $type
     * @param array $choiceValues
     */
    public function __construct(string $description, mixed $defaultValue, string $type, array $choiceValues = [])
    {
        $this->description = $description;
        $this->defaultValue = $defaultValue;
        $this->type = $type;
        $this->choiceValues = $choiceValues;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getChoiceValues(): array
    {
        return $this->choiceValues;
    }
}
