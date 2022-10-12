<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory;

use SprykerSdk\Sdk\Core\Application\Exception\MissingValueException;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Symfony\Component\Console\Question\Question;

class IntQuestionFactory extends StringQuestionFactory
{
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

            if (!is_numeric($value)) {
                throw new MissingValueException('Value type should be integer.');
            }

            return $value;
        });

        return $question;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return ValueTypeEnum::TYPE_INTEGER;
    }
}
