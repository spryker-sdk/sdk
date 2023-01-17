<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc\Infrastructure\Reader;

use InspectionDoc\Entity\InspectionDocInterface;

interface InspectionDocReaderInterface
{
    /**
     * @param string $errorCode
     *
     * @return \InspectionDoc\Entity\InspectionDocInterface|null
     */
    public function findByErrorCode(string $errorCode): ?InspectionDocInterface;
}
