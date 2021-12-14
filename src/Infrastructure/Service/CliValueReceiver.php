<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CliValueReceiver implements ValueReceiverInterface
{
    protected InputInterface $input;

    protected OutputInterface $output;

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
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output)
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
    public function get(string $key): mixed
    {
        return $this->input->getOption($key);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue $receiverValue
     *
     * @return mixed
     */
    public function receiveValue(ReceiverValue $receiverValue): mixed
    {
        $choiceValues = $receiverValue->getChoiceValues();
        $defaultValue = $receiverValue->getDefaultValue();
        $type = $receiverValue->getType();
        $description = $receiverValue->getDescription();
        if (!$defaultValue && $choiceValues) {
            $defaultValue = reset($choiceValues);
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
                    $question = new ChoiceQuestion(
                        $description,
                        $choiceValues,
                        $defaultValue,
                    );

                    break;
                }

                $question = new Question($description, $defaultValue);
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

//            $question->setValidator(function ($value) {
//                if ($value && !is_dir($value)) {
//                    throw new MissingValueException('Directory doesn\'t exist');
//                }
//
//                return $value;
//            });
        }

        return $this->questionHelper->ask(
            $this->input,
            $this->output,
            $question,
        );
    }
}
