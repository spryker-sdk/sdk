<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface;

interface InteractionProcessorInterface
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasRequestItem(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getRequestItem(string $key);

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ReceiverValueInterface $receiverValue
     *
     * @return mixed
     */
    public function receiveValue(ReceiverValueInterface $receiverValue);
}
