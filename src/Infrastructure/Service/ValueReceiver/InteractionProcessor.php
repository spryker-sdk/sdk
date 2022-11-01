<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface;

class InteractionProcessor implements InteractionProcessorInjectorInterface, InteractionProcessorInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $interactionProcessor;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $interactionProcessor
     *
     * @return void
     */
    public function setInteractionProcessor(InteractionProcessorInterface $interactionProcessor): void
    {
        $this->interactionProcessor = $interactionProcessor;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasRequestItem(string $key): bool
    {
        return $this->interactionProcessor->hasRequestItem($key);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getRequestItem(string $key)
    {
        return $this->interactionProcessor->getRequestItem($key);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface $receiverValue
     *
     * @return mixed
     */
    public function receiveValue(ReceiverValueInterface $receiverValue)
    {
        return $this->interactionProcessor->receiveValue($receiverValue);
    }
}
