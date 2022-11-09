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

    /**
     * @param string $projectPath
     * @param string $branch
     *
     * @throws \VcsConnector\Exception\AdapterDoesNotExistException
     *
     * @return void
     */
    public function clone(string $projectPath, string $branch): void;
}
