<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc\Infrastructure\Repository;

use InspectionDoc\Entity\InspectionDocInterface;

interface InspectionDocRepositoryInterface
{
    /**
     * @param string $errorCode
     *
     * @return \InspectionDoc\Entity\InspectionDocInterface|null
     */
    public function findByErrorCode(string $errorCode): ?InspectionDocInterface;
}
