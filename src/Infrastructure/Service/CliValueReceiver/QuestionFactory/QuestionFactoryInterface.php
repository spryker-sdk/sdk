<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory;

use Symfony\Component\Console\Question\Question;

interface QuestionFactoryInterface
{
    /**
     * @param string $description
     * @param array $choices
     * @param mixed $defaultValue
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    public function createQuestion(string $description, array $choices, $defaultValue = null): Question;

    /**
     * @return string
     */
    public static function getType(): string;
}
