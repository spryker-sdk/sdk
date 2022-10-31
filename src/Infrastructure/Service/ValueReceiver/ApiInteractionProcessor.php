<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface;
use SprykerSdk\Sdk\Infrastructure\Event\RequestDataInjectorInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidRequestDataException;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class ApiInteractionProcessor implements InteractionProcessorInterface, RequestDataInjectorInterface
{
    /**
     * @var array
     */
    protected array $data;

    /**
     * @param array $data
     *
     * @return void
     */
    public function setRequestData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return !empty($this->data[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key];
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface $receiverValue
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidRequestDataException
     *
     * @return mixed
     */
    public function receiveValue(ReceiverValueInterface $receiverValue)
    {
        $choiceValues = $receiverValue->getChoiceValues() ? $this->prepareChoiceValues($receiverValue->getChoiceValues()) : [];
        $defaultValue = $receiverValue->getDefaultValue();

        if (!$defaultValue && $choiceValues) {
            $defaultValue = array_key_first($choiceValues);
        }

        if (count($choiceValues) === 1 && in_array($defaultValue, $choiceValues)) {
            return $defaultValue;
        }

        if (isset($this->data[$receiverValue->getAlias()])) {
            return $this->data[$receiverValue->getAlias()];
        }

        if ($receiverValue->getType() === ValueTypeEnum::TYPE_BOOL) {
            return true;
        }

        throw new InvalidRequestDataException($receiverValue->getAlias());
    }

    /**
     * @param array $choices
     *
     * @return array
     */
    protected function prepareChoiceValues(array $choices): array
    {
        if (count($choices) === 0) {
            return $choices;
        }

        $isList = array_keys($choices) === range(0, count($choices) - 1);

        if (!$isList) {
            return $choices;
        }

        return array_combine(range(1, count($choices)), $choices) ?: [];
    }
}
