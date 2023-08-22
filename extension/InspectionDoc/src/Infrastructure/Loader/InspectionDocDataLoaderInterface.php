<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc\Infrastructure\Loader;

interface InspectionDocDataLoaderInterface
{
    /**
     * @return array<string, array<mixed>>
     */
    public function getInspectionDocs(): array;
}
