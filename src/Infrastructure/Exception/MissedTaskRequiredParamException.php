<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Exception;

use Exception;

class MissedTaskRequiredParamException extends Exception
{
    /**
     * @param string $requiredParamKey
     * @param string $taskId
     */
    public function __construct(string $requiredParamKey, string $taskId)
    {
        $message = sprintf(
            'Missed required key `%s` for task `%s`,',
            $requiredParamKey,
            $taskId,
        );

        parent::__construct($message);
    }
}
