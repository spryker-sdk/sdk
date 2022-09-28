<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Enum;

final class TaskType
{
    /**
     * Provides `task_set` task type;
     *
     * @var string
     */
    public const TASK_TYPE__TASK_SET = 'task_set';

    /**
     * Provides `local_cli` task type;
     *
     * @var string
     */
    public const TASK_TYPE__LOCAL_CLI = 'local_cli';

    /**
     * Provides `local_cli_interactive` task type;
     *
     * @var string
     */
    public const TASK_TYPE__LOCAL_CLI_INTERACTIVE = 'local_cli_interactive';
}
