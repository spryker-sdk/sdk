<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Exception;

use Exception;

class InvalidTaskTypeException extends Exception
{
    /**
     * @param string $taskType
     */
    public function __construct(string $taskType)
    {
        $message = sprintf(
            'Unknown task type `%s` provided',
            $taskType,
        );

        parent::__construct($message);
    }
}
