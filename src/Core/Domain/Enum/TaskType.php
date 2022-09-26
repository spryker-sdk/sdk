<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Enum;

final class TaskType
{
    /**
     * Provides `task-set` task type.
     *
     * @var string
     */
    public const TYPE_TASK_SET = 'task_set';

    /**
     * Provides `php` task type
     *
     * @var string
     */
    public const TYPE_PHP_TASK = 'php';

    /**
     * Provides `yaml` task type.
     *
     * @var string
     */
    public const TYPE_YAM_TASK = 'yaml';
}
