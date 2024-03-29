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
     * @var string
     */
    protected string $alias;

    /**
     * @param string $alias
     * @param string $description
     * @param mixed $defaultValue
     * @param string $type
     * @param array $choiceValues
     */
    public function __construct(string $alias, string $description, $defaultValue, string $type, array $choiceValues = [])
    {
        $this->alias = $alias;
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
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }
}
