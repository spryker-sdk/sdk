<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Workflow;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Extension\Exception\UniqueValueException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;
use SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface;

class InteractionAnswerBasedTransitionResolver implements TransitionResolverInterface
{
    /**
     * @var string
     */
    protected const RESOLVER_NAME = 'interactive';

    /**
     * @var string
     */
    public const QUESTION = 'question';

    /**
     * @var string
     */
    public const CHOICES = 'choices';

    /**
     * @var \SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface
     */
    protected ValueReceiverInterface $cliValueReceiver;

    /**
     * @param \SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface $cliValueReceiver
     */
    public function __construct(ValueReceiverInterface $cliValueReceiver)
    {
        $this->cliValueReceiver = $cliValueReceiver;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::RESOLVER_NAME;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settings
     *
     * @return string|null
     */
    public function resolveTransition(ContextInterface $context, array $settings): ?string
    {
        $choiceValues = $this->getChoiceValues($settings);
        $flippedValues = array_flip($choiceValues);

        $answer = $this->cliValueReceiver->receiveValue(new ReceiverValue(
            $settings[static::QUESTION] ?? '',
            null,
            'string',
            array_values($choiceValues),
        ));

        return $flippedValues[$answer];
    }

    /**
     * @param array $settings
     *
     * @throws \SprykerSdk\Sdk\Extension\Exception\UniqueValueException
     *
     * @return array<string, string>
     */
    protected function getChoiceValues(array $settings): array
    {
        $choices = $settings[static::CHOICES] ?? [];
        $choiceValues = [];
        foreach ($choices as $transition => $choice) {
            $choiceValues[(string)$transition] = (string)$choice['description'];
        }

        $duplicates = array_unique(array_diff_assoc($choiceValues, array_unique($choiceValues)));
        if ($duplicates) {
            throw new UniqueValueException(sprintf('Descriptions for choices must be unique: `%s`', implode('`,`', $duplicates)));
        }

        return $choiceValues;
    }
}
