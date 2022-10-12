<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory;

use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class BooleanQuestionFactory implements QuestionFactoryInterface
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
        return new ConfirmationQuestion($description, (bool)$defaultValue);
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return ValueTypeEnum::TYPE_BOOLEAN;
    }
}
