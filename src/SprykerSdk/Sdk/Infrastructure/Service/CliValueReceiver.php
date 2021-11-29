<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CliValueReceiver implements ValueReceiverInterface
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
     * @param \Symfony\Component\Console\Helper\SymfonyQuestionHelper $questionHelper
     */
    public function __construct(
        protected SymfonyQuestionHelper $questionHelper
    ) {}

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
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
     * @param string $description
     * @param mixed $defaultValue
     * @param string $type
     * @param array $choiceValues
     *
     * @return mixed
     */
    public function receiveValue(string $description, mixed $defaultValue, string $type, array $choiceValues = []): mixed
    {
        if (count($choiceValues) === 1 && in_array($defaultValue, $choiceValues)) {
            return $defaultValue;
        }

        switch ($type) {
            case 'bool':

                $question = new ConfirmationQuestion($description, (bool)$defaultValue);

                break;
            default:
                if ($choiceValues) {
                    $question = new ChoiceQuestion(
                        $description,
                        $choiceValues,
                        $defaultValue
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
                if ($value === '') {
                    throw new MissingValueException('Value is required');
                }

                return $value;
            });
        }

        if ($type === 'path') {
            $question->setAutocompleterCallback(function (string $userInput): array {
                $inputPath = preg_replace('%(/|^)[^/]*$%', '$1', $userInput);
                $foundFilesAndDirs = @scandir($inputPath ?: '.') ?: [];

                return array_map(function ($dirOrFile) use ($inputPath) {
                    return $inputPath . $dirOrFile;
                }, $foundFilesAndDirs);
            });

            $question->setValidator(function ($value) {
                if ($value && !\is_dir($value)) {
                    throw new MissingValueException('Directory doesn\'t exist');
                }

                return $value;
            });
        }

        return $this->questionHelper->ask(
            $this->input,
            $this->output,
            $question
        );
    }
}
