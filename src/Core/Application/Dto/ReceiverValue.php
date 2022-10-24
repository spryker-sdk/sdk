<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto;

class ReceiverValue implements ReceiverValueInterface
{
    /**
     * @var string
     */
    protected string $description;

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var array
     */
    protected array $choiceValues;

    /**
     * @var string|null
     */
    protected ?string $alias;

    /**
     * @param string $description
     * @param mixed $defaultValue
     * @param string $type
     * @param array $choiceValues
     * @param string|null $alias
     */
    public function __construct(string $description, $defaultValue, string $type, array $choiceValues = [], ?string $alias = null)
    {
        $this->description = $description;
        $this->defaultValue = $defaultValue;
        $this->type = $type;
        $this->choiceValues = $choiceValues;
        $this->alias = $alias;
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
    public function getDefaultValue()
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

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }
}
