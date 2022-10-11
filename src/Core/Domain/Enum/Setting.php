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
     * Path setting key for extension directories.
     *
     * @var string
     */
    public const PATH_EXTENSION_DIRS = 'extension_dirs';

    /**
     * Path setting key for sdk directory.
     *
     * @var string
     */
    public const PATH_SDK_DIR = 'sdk_dir';

    /**
     * Path setting key for project directory.
     *
     * @var string
     */
    public const PATH_PROJECT_DIR = 'project_dir';

    /**
     * Path setting key for report usage statistic.
     *
     * @var string
     */
    public const PATH_REPORT_USAGE_STATISTICS = 'report_usage_statistics';

    /**
     * Path setting key for core namespaces.
     *
     * @var string
     */
    public const PATH_CORE_NAMESPACE = 'coreNamespaces';

    /**
     * Path setting key for report project namespaces.
     *
     * @var string
     */
    public const PATH_PROJECT_NAMESPACES = 'projectNamespaces';

    /**
     * Path setting key for default violation format.
     *
     * @var string
     */
    public const PATH_DEFAULT_VIOLATION_OUTPUT_PATH = 'default_violation_output_format';

    /**
     * Path setting key for report directory.
     *
     * @var string
     */
    public const PATH_REPORT_DIR = 'report_dir';

    /**
     * Path setting key for project uuid id.
     *
     * @var string
     */
    public const PATH_PROJECT_KEY = 'project_key';

    /**
     * Path setting key for workflow list.
     *
     * @var string
     */
    public const PATH_WORKFLOW = 'workflow';

    /**
     * Path setting key for qa automation command list.
     *
     * @var string
     */
    public const PATH_QA_TASKS = 'qa_tasks';

    /**
     * Path setting key for developer email.
     *
     * @var string
     */
    public const PATH_DEVELOPER_EMAIL = 'developer_email';

    /**
     * Path setting key for developer github account.
     *
     * @var string
     */
    public const PATH_DEVELOPER_GITHUB_ACCOUNT = 'developer_github_account';
}
