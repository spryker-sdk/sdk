<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class NeedToAskQuestion
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
     *
     * @return bool
     */
    public function ask(string $valueId): bool
    {
         return $this->interactionProcessor->receiveValue(
             new ReceiverValue(
                 sprintf('Would you like to set value for `%s`?', $valueId),
                 false,
                 ValueTypeEnum::TYPE_BOOL,
             ),
         );
    }
}
