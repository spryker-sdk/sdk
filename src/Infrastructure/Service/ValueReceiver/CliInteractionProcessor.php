<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface;
use SprykerSdk\Sdk\Infrastructure\Injector\InputInjectorInterface;
use SprykerSdk\Sdk\Infrastructure\Injector\OutputInjectorInterface;
use SprykerSdk\Sdk\Infrastructure\Injector\RequestDataInjectorInterface;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CliInteractionProcessor implements InteractionProcessorInterface, InputInjectorInterface, OutputInjectorInterface, RequestDataInjectorInterface
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected InputInterface $input;

    /**
     * @var array<array>
     */
    protected array $requestData = [];

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var \Symfony\Component\Console\Helper\SymfonyQuestionHelper
     */
    protected SymfonyQuestionHelper $questionHelper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactoryRegistry
     */
    protected QuestionFactoryRegistry $questionFactoryRegistry;

    /**
     * @param \Symfony\Component\Console\Helper\SymfonyQuestionHelper $questionHelper
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactoryRegistry $questionFactoryRegistry
     */
    public function __construct(SymfonyQuestionHelper $questionHelper, QuestionFactoryRegistry $questionFactoryRegistry)
    {
        $this->questionHelper = $questionHelper;
        $this->questionFactoryRegistry = $questionFactoryRegistry;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return void
     */
    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

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

        $question = $this->questionFactoryRegistry
            ->getQuestionFactoryByType($receiverValue->getType())
            ->createQuestion(
                $receiverValue->getDescription(),
                $choiceValues,
                $defaultValue,
            );

        $value = $this->questionHelper->ask(
            $this->input,
            $this->output,
            $question,
        );

        if ($question->isMultiline()) {
            return (array)preg_split("/\r\n|\n|\r/", $value);
        }

        return $value;
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
