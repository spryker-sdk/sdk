<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Enum;

final class Setting
{
    /**
     * Type for shared settings in repository.
     *
     * @var string
     */
    public const SETTING_TYPE_SHARED = 'shared';

    /**
     * Type for local settings.
     *
     * @var string
     */
    public const SETTING_TYPE_LOCAL = 'local';

    /**
     * Type for sdk settings in repository.
     *
     * @var string
     */
    public const SETTING_TYPE_SDK = 'sdk';

    /**
     * @var string
     */
    public const PATH_EXTENSION_DIRS = 'extension_dirs';

    /**
     * @var string
     */
    public const PATH_SDK_DIR = 'sdk_dir';

    /**
     * @var string
     */
    public const PATH_PROJECT_DIR = 'project_dir';

    /**
     * @var string
     */
    public const PATH_REPORT_USAGE_STATISTICS = 'report_usage_statistics';

    /**
     * @var string
     */
    public const PATH_CORE_NAMESPACE = 'coreNamespaces';

    /**
     * @var string
     */
    public const PATH_PROJECT_NAMESPACES = 'projectNamespaces';

    /**
     * @var string
     */
    public const PATH_DEFAULT_VIOLATION_OUTPUT_PATH = 'default_violation_output_format';

    /**
     * @var string
     */
    public const PATH_REPORT_DIR = 'report_dir';

    /**
     * @var string
     */
    public const PATH_PROJECT_KEY = 'project_key';

    /**
     * @var string
     */
    public const PATH_WORKFLOW = 'workflow';

    /**
     * @var string
     */
    public const PATH_QA_TASKS = 'qa_tasks';
}
