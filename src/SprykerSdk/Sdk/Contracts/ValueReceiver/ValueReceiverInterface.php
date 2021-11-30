<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\ValueReceiver;

use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;

interface ValueReceiverInterface
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
    public function get(string $key): mixed;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue $receiverValue
     *
     * @return mixed
     */
    public function receiveValue(ReceiverValue $receiverValue): mixed;
}
