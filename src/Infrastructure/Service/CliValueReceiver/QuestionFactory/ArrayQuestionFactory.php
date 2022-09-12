<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory;

use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class ArrayQuestionFactory extends StringQuestionFactory
{
    /**
     * @var string
     */
    public const CHOICE_DESCRIPTION_SUFFIX = ' (Multiselect format: 1,2,3)';

    /**
     * @param string $description
     * @param array $choices
     * @param mixed $defaultValue
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    public function createQuestion(string $description, array $choices, $defaultValue = null): Question
    {
        if (is_array($defaultValue)) {
            $defaultValue = implode(',', array_keys(array_intersect($choices, $defaultValue)));
        }

        return count($choices) > 0
            ? $this->createChoiceQuestion($description, $choices, $defaultValue)
            : $this->createLineQuestion($description, $defaultValue);
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
        $description .= static::CHOICE_DESCRIPTION_SUFFIX;
        $question = parent::createChoiceQuestion($description, $choices, $defaultValue);

        $question->setMultiselect(true);

        return $question;
    }

    /**
     * @param string $description
     * @param mixed $defaultValue
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    protected function createLineQuestion(string $description, $defaultValue = null): Question
    {
        $question = parent::createLineQuestion($description, $defaultValue);
        $question->setMultiline(true);

        return $question;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return ValueTypeEnum::TYPE_ARRAY;
    }
}
