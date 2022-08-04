<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\SdkTasksBundle\ValueResolver;

use SprykerSdk\Sdk\Extension\ValueResolver\BusinessModelValueResolver;

class InternalProjectValueResolver extends BusinessModelValueResolver
{
    /**
     * @var string
     */
    public const ID = 'INTERNAL_PROJECT';

    /**
     * @var string
     */
    public const ALIAS = 'internal_project_url';

    /**
     * @var array<string, string>
     */
    protected const REPOSITORIES = [
        'b2b' => 'git@github.com:spryker/b2b-demo-shop-internal.git',
        'b2c' => 'git@github.com:spryker/b2c-demo-shop-internal.git',
        'suite-nonsplit' => 'git@github.com:spryker/suite-nonsplit.git',
    ];
}
