<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

interface RequestDataReceiverInterface
{
    /**
     * @param array $output
     *
     * @return void
     */
    public function setRequestData(array $output): void;
}
