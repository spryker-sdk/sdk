<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface;

interface ReceiverInterface
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface $receiverValue
     *
     * @return mixed
     */
    public function receiveValue(ReceiverValueInterface $receiverValue);
}
