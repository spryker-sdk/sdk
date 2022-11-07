<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Vcs\Adapter;

interface VcsInterface
{
    /**
     * @return string
     */
    public static function getName(): string;
}
