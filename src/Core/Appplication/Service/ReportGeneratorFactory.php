<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ReportGeneratorFactory
{
    /**
     * @var iterable<\SprykerSdk\SdkContracts\Report\ReportGeneratorResolverInterface>
     */
    protected iterable $reportGeneratorResolvers;

    /**
     * @param iterable<\SprykerSdk\SdkContracts\Report\ReportGeneratorResolverInterface> $reportGeneratorResolvers
     */
    public function __construct(iterable $reportGeneratorResolvers)
    {
        $this->reportGeneratorResolvers = $reportGeneratorResolvers;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return array<\SprykerSdk\SdkContracts\Report\ReportGeneratorInterface>
     */
    public function getReportGeneratorsByContext(ContextInterface $context): array
    {
        $reportGenerators = [];

        foreach ($this->reportGeneratorResolvers as $reportGeneratorResolver) {
            $reportGenerator = $reportGeneratorResolver->resolveByContext($context);

            if ($reportGenerator === null) {
                continue;
            }

            $reportGenerators[] = $reportGenerator;
        }

        return $reportGenerators;
    }
}
