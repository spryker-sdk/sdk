<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo;

use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Service\ProjectInfo\ProjectInfoFetcherInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectInfo\ProjectInfo;

class ProjectInfoFetcher implements ProjectInfoFetcherInterface
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfoFetcherStrategyInterface>
     */
    protected iterable $projectInfoFetcherStrategies;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfoFetcherStrategyInterface> $projectInfoFetcherStrategies
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(iterable $projectInfoFetcherStrategies, LoggerInterface $logger)
    {
        $this->projectInfoFetcherStrategies = $projectInfoFetcherStrategies;
        $this->logger = $logger;
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
                $this->logger->error($e->getMessage());

                continue;
            }
        }

        return null;
    }
}
