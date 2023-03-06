<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class NewCollectionItemQuestion
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $interactionProcessor;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $interactionProcessor
     */
    public function __construct(InteractionProcessorInterface $interactionProcessor)
    {
        $this->interactionProcessor = $interactionProcessor;
    }

    /**
     * @param string $valueId
     * @param bool $isFirstItem
     *
     * @return bool
     */
    public function ask(string $valueId, bool $isFirstItem): bool
    {
        return $this->interactionProcessor->receiveValue(
            new ReceiverValue(
                'new-value',
                sprintf($isFirstItem ? 'Would you like to add `%s`?' : 'Would you like to add more `%s`?', $valueId),
                false,
                ValueTypeEnum::TYPE_BOOL,
            ),
        );
    }
}
