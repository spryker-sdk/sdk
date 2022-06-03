<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Reader;

interface WorkflowReaderInterface
{
    /**
     * @return array
     */
    public function loadWorkflows(): array;
}
