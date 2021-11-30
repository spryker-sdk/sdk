<?php

namespace SprykerSdk\Sdk\Core\Appplication\Dto;

class ReceiverValue
{
    public string $description;

    public mixed $defaultValue;

    public string $type;

    public array $choiceValues;

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
