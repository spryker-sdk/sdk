<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;

use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\QuestionFactoryInterface;
use Traversable;

class QuestionFactoryRegistry
{
    /**
     * @var array<string, \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\QuestionFactoryInterface>
     */
    protected array $questionFactories;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\QuestionFactoryInterface> $questionFactories
     */
    public function __construct(iterable $questionFactories)
    {
        $this->questionFactories = $questionFactories instanceof Traversable ? iterator_to_array($questionFactories) : $questionFactories;
    }

    /**
     * @param string $questionType
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\QuestionFactoryInterface
     */
    public function getQuestionFactoryByType(string $questionType): QuestionFactoryInterface
    {
        if (!isset($this->questionFactories[$questionType])) {
            throw new InvalidArgumentException(sprintf('Unable to find %s question factory type', $questionType));
        }

        return $this->questionFactories[$questionType];
    }
}
