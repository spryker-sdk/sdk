<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Service\ProjectInfo\ProjectInfoInterface;
use Symfony\Component\Process\Process;
use Throwable;

class GitProjectInfoFetcher implements ProjectInfoFetcherStrategyInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfo|null
     */
    protected ?ProjectInfoInterface $projectInfo = null;

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfo
     */
    public function fetchProjectInfo(): ProjectInfoInterface
    {
        if ($this->projectInfo === null) {
            $this->projectInfo = $this->getProjectInfo();
        }

        return $this->projectInfo;
    }

    /**
     * @throws \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\FetchDataException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfo
     */
    protected function getProjectInfo(): ProjectInfoInterface
    {
        $process = new Process(['git', 'remote', 'get-url', '--push', 'origin']);

        try {
            $process->mustRun();

            return new ProjectInfo(trim($process->getOutput()));
        } catch (Throwable $e) {
            throw new FetchDataException($e->getMessage(), 0, $e);
        }
    }
}
