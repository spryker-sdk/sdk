<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ActionApproverInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliReceiver;

class ActionApprover implements ActionApproverInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliReceiver
     */
    protected CliReceiver $cliValueReceiver;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliReceiver $cliValueReceiver
     */
    public function __construct(CliReceiver $cliValueReceiver)
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
                $message,
                true,
                'boolean',
            ),
        );
    }
}
