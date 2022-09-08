<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingValueException;
use SprykerSdk\Sdk\Infrastructure\Event\InputOutputReceiverInterface;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CliReceiver implements ReceiverInterface, InputOutputReceiverInterface
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
     * @param \Symfony\Component\Console\Helper\SymfonyQuestionHelper $questionHelper
     */
    public function __construct(SymfonyQuestionHelper $questionHelper)
    {
        $this->questionHelper = $questionHelper;
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
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface $receiverValue
     *
     * @return mixed
     */
    public function receiveValue(ReceiverValueInterface $receiverValue)
    {
        $choiceValues = $receiverValue->getChoiceValues() ? $this->prepareChoiceValues($receiverValue->getChoiceValues()) : [];
        $defaultValue = $receiverValue->getDefaultValue();
        $type = $receiverValue->getType();
        $description = $receiverValue->getDescription();
        if (!$defaultValue && $choiceValues) {
            $defaultValue = array_key_first($choiceValues);
        }

        if (count($choiceValues) === 1 && in_array($defaultValue, $choiceValues)) {
            return $defaultValue;
        }

        switch ($type) {
            case 'boolean':
                $question = new ConfirmationQuestion($description, (bool)$defaultValue);

                break;
            default:
                if ($choiceValues) {
                    if ($type === 'array') {
                        $description .= ' (Multiselect format: 1,2,3)';
                        if (is_array($defaultValue)) {
                            $defaultValue = implode(',', array_keys(array_intersect($choiceValues, $defaultValue)));
                        }
                    }
                    $question = new ChoiceQuestion(
                        $description,
                        $choiceValues,
                        $defaultValue,
                    );

                    if ($type === 'array') {
                        $question->setMultiselect(true);
                    }

                    break;
                }

                $question = new Question($description, $defaultValue);
                if ($type === 'array') {
                    $question->setMultiline(true);
                }
                $question->setNormalizer(function ($value) {
                    return $value ?: '';
                });
        }
        if ($defaultValue === null) {
            $question->setValidator(function ($value) {
                if ($value === '' || $value === null) {
                    throw new MissingValueException('Value is required');
                }

                return $value;
            });
        }

        if ($type === 'path') {
            $question->setAutocompleterCallback(function (string $userInput): array {
                $inputPath = preg_replace('%(/|^)[^/]*$%', '$1', $userInput);
                //Autocompletion is an optional convenience feature that should not fail the whole command run
                //@phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
                $foundFilesAndDirs = @scandir($inputPath ?: '.') ?: [];

                return array_map(function ($dirOrFile) use ($inputPath) {
                    return $inputPath . $dirOrFile;
                }, $foundFilesAndDirs);
            });
        }

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
