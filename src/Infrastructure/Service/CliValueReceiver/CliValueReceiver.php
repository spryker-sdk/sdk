<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;

use SprykerSdk\Sdk\Infrastructure\Event\InputOutputReceiverInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ReceiverValueInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CliValueReceiver implements ValueReceiverInterface, InputOutputReceiverInterface
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected InputInterface $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var \Symfony\Component\Console\Helper\SymfonyQuestionHelper
     */
    protected SymfonyQuestionHelper $questionHelper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactoryRegistry
     */
    protected QuestionFactoryRegistry $questionFactoryRegistry;

    /**
     * @param \Symfony\Component\Console\Helper\SymfonyQuestionHelper $questionHelper
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactoryRegistry $questionFactoryRegistry
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
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->input->hasOption($key) && $this->input->getOption($key) !== null;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->input->getOption($key);
    }

    /**
     * @param \SprykerSdk\SdkContracts\ValueReceiver\ReceiverValueInterface $receiverValue
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
