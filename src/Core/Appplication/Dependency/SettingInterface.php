<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

interface SettingInterface
{
    /**
     * @param array $settings
     *
     * @return void
     */
    public function initialize(array $settings): void;
}
