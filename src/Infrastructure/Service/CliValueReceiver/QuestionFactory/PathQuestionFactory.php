<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory;

use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionTypeEnum;
use Symfony\Component\Console\Question\Question;

class PathQuestionFactory extends GenericQuestionFactory
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

        $question->setAutocompleterCallback([$this, 'getAutocompleteCallback']);

        return $question;
    }

    /**
     * @param string $userInput
     *
     * @return array<string>
     */
    public function getAutocompleteCallback(string $userInput): array
    {
        $inputPath = preg_replace('%(/|^)[^/]*$%', '$1', $userInput);
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
        return QuestionTypeEnum::TYPE_PATH;
    }
}
