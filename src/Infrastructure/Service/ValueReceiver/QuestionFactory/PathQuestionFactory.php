<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory;

use SprykerSdk\Sdk\Core\Application\Exception\MissingValueException;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Symfony\Component\Console\Question\Question;

class PathQuestionFactory extends StringQuestionFactory
{
    /**
     * @param string $description
     * @param array $choices
     * @param mixed $defaultValue
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    public function createQuestion(string $description, array $choices, $defaultValue = null): Question
    {
        $question = parent::createQuestion($description, $choices, $defaultValue);

        $question->setAutocompleterCallback([$this, 'autocompleteInput']);

        return $this->addValidator($question);
    }

    /**
     * @param \Symfony\Component\Console\Question\Question $question
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    protected function addValidator(Question $question): Question
    {
        $question->setValidator(function ($value) {
            if (!$this->isPassValid($value)) {
                throw new MissingValueException('Path ../ is forbidden due to security reasons.');
            }

            if (!$this->isAbsolutePath($value)) {
                throw new MissingValueException('Absolute path is forbidden due to security reasons.');
            }

            return $value;
        });

        return $question;
    }

    /**
     * @param string $userInput
     *
     * @return array<string>
     */
    public function autocompleteInput(string $userInput): array
    {
        $inputPath = (string)preg_replace('%(/|^)[^/]*$%', '$1', $userInput);

        if (!$this->isPassValid($inputPath)) {
            return [];
        }

        if (!$this->isAbsolutePath($inputPath)) {
            return [];
        }

        //Autocompletion is an optional convenience feature that should not fail the whole command run
        //@phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
        $foundFilesAndDirs = @scandir($inputPath ?: '.') ?: [];

        return array_map(static function ($dirOrFile) use ($inputPath) {
            return $inputPath . $dirOrFile;
        }, $foundFilesAndDirs);
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return ValueTypeEnum::TYPE_PATH;
    }

    /**
     * @param string $inputPath
     *
     * @return bool
     */
    protected function isPassValid(string $inputPath): bool
    {
        return strpos($inputPath, '..') === false;
    }

    /**
     * @param string $inputPath
     *
     * @return bool
     */
    protected function isAbsolutePath(string $inputPath): bool
    {
        return strpos($inputPath, '/') === false;
    }
}
