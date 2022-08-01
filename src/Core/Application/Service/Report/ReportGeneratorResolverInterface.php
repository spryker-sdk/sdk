<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\Report;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

interface ReportGeneratorResolverInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Core\Application\Service\Report\ReportGeneratorInterface|null
     */
    public function resolveByContext(ContextInterface $context): ?ReportGeneratorInterface;
}
