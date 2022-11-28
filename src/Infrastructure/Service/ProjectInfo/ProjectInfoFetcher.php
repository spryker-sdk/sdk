<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo;

use SprykerSdk\Sdk\Core\Application\Dependency\Service\ProjectInfo\ProjectInfoFetcherInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectInfo\ProjectInfo;
use SprykerSdk\Sdk\Infrastructure\Logger\ErrorLoggerFactoryInterface;

class ProjectInfoFetcher implements ProjectInfoFetcherInterface
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfoFetcherStrategyInterface>
     */
    protected iterable $projectInfoFetcherStrategies;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfoFetcherStrategyInterface> $projectInfoFetcherStrategies
     * @param \SprykerSdk\Sdk\Infrastructure\Logger\ErrorLoggerFactoryInterface $errorLoggerFactory
     */
    public function __construct(iterable $projectInfoFetcherStrategies, ErrorLoggerFactoryInterface $errorLoggerFactory)
    {
        $this->projectInfoFetcherStrategies = $projectInfoFetcherStrategies;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dto\ProjectInfo\ProjectInfo|null
     */
    public function fetchProjectInfo(): ?ProjectInfo
    {
        foreach ($this->projectInfoFetcherStrategies as $projectInfoFetcherStrategy) {
            try {
                return $projectInfoFetcherStrategy->fetchProjectInfo();
            } catch (FetchDataException $e) {
                continue;
            }
        }

        return null;
    }
}
