<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidRequestDataException;
use SprykerSdk\Sdk\Infrastructure\Injector\RequestDataInjectorInterface;

class ApiInteractionProcessor implements InteractionProcessorInterface, RequestDataInjectorInterface
{
    /**
     * @var array
     */
    protected array $requestData = [];

    /**
     * @param array $requestData
     *
     * @return void
     */
    public function setRequestData(array $requestData): void
    {
        $this->requestData = $requestData;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasRequestItem(string $key): bool
    {
        return !empty($this->requestData[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getRequestItem(string $key)
    {
        return $this->requestData[$key];
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
        if (isset($this->requestData[$receiverValue->getAlias()])) {
            return $this->requestData[$receiverValue->getAlias()];
        }

        $choiceValues = $this->prepareChoiceValues($receiverValue->getChoiceValues());
        $defaultValue = $receiverValue->getDefaultValue();

        if (!$defaultValue && $choiceValues) {
            $defaultValue = array_key_first($choiceValues);
        }

        if (count($choiceValues) === 1 && in_array($defaultValue, $choiceValues)) {
            return $defaultValue;
        }

        if ($receiverValue->getDefaultValue() !== null) {
            return $receiverValue->getDefaultValue();
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

        return array_combine(range(1, count($choices)), $choices);
    }
}
