<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Hello\Tasks\Commands;

class HelloStageACommand extends GreeterCommand
{
    /**
     * @return string
     */
    public function getStage(): string
    {
        return 'stageA';
    }
}
