<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

interface TaskSetInterface extends TaskInterface
{
    /**
     * @param array<string> $tags
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\TaggedTaskInterface>
     */
    public function getTasks(array $tags = []): array;
}
