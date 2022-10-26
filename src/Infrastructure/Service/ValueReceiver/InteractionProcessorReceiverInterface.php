<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Infrastructure\Event\ReceiverInterface;

interface InteractionProcessorReceiverInterface extends ReceiverInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $interactionProcessor
     *
     * @return void
     */
    public function setInteractionProcessor(InteractionProcessorInterface $interactionProcessor): void;
}
