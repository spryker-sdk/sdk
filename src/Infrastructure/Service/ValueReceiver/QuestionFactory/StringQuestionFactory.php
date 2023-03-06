<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory;

use SprykerSdk\Sdk\Core\Application\Exception\MissingValueException;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class StringQuestionFactory implements QuestionFactoryInterface
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
        $question = count($choices) === 0
            ? $this->createLineQuestion($description, $defaultValue)
            : $this->createChoiceQuestion($description, $choices, $defaultValue);

        if ($defaultValue !== null) {
            return $question;
        }

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
            if ($value === '' || $value === null) {
                throw new MissingValueException('Value is required.');
            }

            return $value;
        });

        return $question;
    }

    /**
     * @param string $description
     * @param array $choices
     * @param mixed $defaultValue
     *
     * @return \Symfony\Component\Console\Question\ChoiceQuestion
     */
    protected function createChoiceQuestion(string $description, array $choices, $defaultValue = null): ChoiceQuestion
    {
        return new ChoiceQuestion(
            $description,
            $choices,
            $defaultValue,
        );
    }

    /**
     * @param string $description
     * @param mixed $defaultValue
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    protected function createLineQuestion(string $description, $defaultValue = null): Question
    {
        $question = new Question($description, $defaultValue);

        $question->setNormalizer(function ($value) {
            return $value ?: '';
        });

        return $question;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return ValueTypeEnum::TYPE_STRING;
    }
}
