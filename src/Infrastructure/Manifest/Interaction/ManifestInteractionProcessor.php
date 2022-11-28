<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\Interaction;

use InvalidArgumentException;
use SplStack;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\CallbackValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ReceivedValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\StaticValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ValueCollection;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NeedToAskQuestion;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NewCollectionItemQuestion;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\ValueQuestion;

class ManifestInteractionProcessor implements ManifestInteractionProcessorInterface
{
    /**
     * @var string
     */
    protected const NOT_SET_VALUE = '__NOT_SET__';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NeedToAskQuestion
     */
    protected NeedToAskQuestion $needToAskQuestion;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\ValueQuestion
     */
    protected ValueQuestion $valueQuestion;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NewCollectionItemQuestion
     */
    protected NewCollectionItemQuestion $newCollectionItemQuestion;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NeedToAskQuestion $needToAskQuestion
     * @param \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\ValueQuestion $valueQuestion
     * @param \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NewCollectionItemQuestion $newCollectionItemQuestion
     */
    public function __construct(
        NeedToAskQuestion $needToAskQuestion,
        ValueQuestion $valueQuestion,
        NewCollectionItemQuestion $newCollectionItemQuestion
    ) {
        $this->needToAskQuestion = $needToAskQuestion;
        $this->valueQuestion = $valueQuestion;
        $this->newCollectionItemQuestion = $newCollectionItemQuestion;
    }

    /**
     * @param array $valueConfigMap
     *
     * @return array
     */
    public function receiveValues(array $valueConfigMap): array
    {
        return $this->receiveValue('', $valueConfigMap, new SplStack(), []);
    }

    /**
     * @param string $id
     * @param \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig|array $valueConfig
     * @param \SplStack $keyStack
     * @param array $receivedValues
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function receiveValue(string $id, $valueConfig, SplStack $keyStack, array $receivedValues): array
    {
        switch (true) {
            case $valueConfig instanceof ReceivedValue:
                $value = $this->processReceivedValue($id, $valueConfig);

                return $value !== static::NOT_SET_VALUE
                    ? $this->setValueByKeyStack($receivedValues, $keyStack, $value)
                    : $receivedValues;
            case $valueConfig instanceof StaticValue:
                return $this->setValueByKeyStack($receivedValues, $keyStack, $valueConfig->getValue());
            case $valueConfig instanceof ValueCollection:
                return $this->processCollection($id, $valueConfig, $keyStack, $receivedValues);
            case $valueConfig instanceof CallbackValue:
                return $this->processCallbackValue($id, $valueConfig, $keyStack, $receivedValues);
            case is_array($valueConfig):
                return $this->processNested($valueConfig, $keyStack, $receivedValues);
            default:
                throw new InvalidArgumentException(sprintf('Invalid mapping in `%s` node', $id));
        }
    }

    /**
     * @param array $value
     * @param \SplStack $keyStack
     * @param array $receivedValues
     *
     * @return array
     */
    protected function processNested(array $value, SplStack $keyStack, array $receivedValues): array
    {
        foreach ($value as $itemId => $itemValue) {
            $keyStack->push($itemId);

            $receivedValues = $this->receiveValue($itemId, $itemValue, $keyStack, $receivedValues);

            $keyStack->pop();
        }

        return $receivedValues;
    }

    /**
     * @param string $id
     * @param \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ValueCollection $valuesCollection
     * @param \SplStack $keyStack
     * @param array $receivedValues
     *
     * @return array
     */
    protected function processCollection(
        string $id,
        ValueCollection $valuesCollection,
        SplStack $keyStack,
        array $receivedValues
    ): array {
        $index = 0;

        while (
            ($index === 0 && $valuesCollection->isMinOneItemRequired())
            || $this->newCollectionItemQuestion->ask($id, $index === 0)
        ) {
            $keyStack->push($index);

            $itemConfig = $valuesCollection->isFlatList()
                ? $valuesCollection->getValueConfigs()[0] ?? []
                : $valuesCollection->getValueConfigs();

            $receivedValues = $this->receiveValue($id, $itemConfig, $keyStack, $receivedValues);

            $keyStack->pop();

            ++$index;
        }

        return $receivedValues;
    }

    /**
     * @param string $id
     * @param \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ReceivedValue $valueConfig
     *
     * @return mixed
     */
    protected function processReceivedValue(string $id, ReceivedValue $valueConfig)
    {
        if (!$valueConfig->isRequired() && !$this->needToAskQuestion->ask($id)) {
            return static::NOT_SET_VALUE;
        }

        return $this->valueQuestion->ask($valueConfig->getValue());
    }

    /**
     * @param string $id
     * @param \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\CallbackValue $valueConfig
     * @param \SplStack $keyStack
     * @param array $receivedValues
     *
     * @return array
     */
    protected function processCallbackValue(string $id, CallbackValue $valueConfig, SplStack $keyStack, array $receivedValues): array
    {
        $valueConfig = $valueConfig->getCallback()($receivedValues);

        return $this->receiveValue($id, $valueConfig, $keyStack, $receivedValues);
    }

    /**
     * @param array $receivedValues
     * @param \SplStack $keyStack
     * @param mixed $value
     *
     * @return array
     */
    protected function setValueByKeyStack(array $receivedValues, SplStack $keyStack, $value): array
    {
        $where = &$receivedValues;

        foreach (array_reverse(iterator_to_array($keyStack)) as $key) {
            $where = &$where[$key];
        }

        $where = $value;

        return $receivedValues;
    }
}
