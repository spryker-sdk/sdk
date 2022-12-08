<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelation as CoreTaskSetRelation;

class TaskSetTaskRelation extends CoreTaskSetRelation
{
    /**
     * @var int
     */
    protected int $id;
}
