<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ActionApproverInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class ActionApprover implements ActionApproverInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $cliValueReceiver;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $cliValueReceiver
     */
    public function __construct(InteractionProcessorInterface $cliValueReceiver)
    {
        $this->cliValueReceiver = $cliValueReceiver;
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    public function approve(string $message): bool
    {
        return (bool)$this->cliValueReceiver->receiveValue(
            new ReceiverValue(
                'approve',
                $message,
                true,
                ValueTypeEnum::TYPE_BOOL,
            ),
        );
    }
}
