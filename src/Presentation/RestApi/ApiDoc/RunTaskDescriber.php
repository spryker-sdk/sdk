<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\ApiDoc;

use OpenApi\Annotations\OpenApi;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

class RunTaskDescriber extends BaseDescriber
{
    /**
     * @var string
     */
    protected const RUN_TASK_ROUTE = '/api/v1/task/%s';

    /**
     * @var string
     */
    protected const HTTP_METHOD = 'POST';

    /**
     * @var \Symfony\Component\Console\CommandLoader\CommandLoaderInterface
     */
    protected CommandLoaderInterface $commandLoader;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param \Symfony\Component\Console\CommandLoader\CommandLoaderInterface $commandLoader
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
     */
    public function __construct(CommandLoaderInterface $commandLoader, TaskRepositoryInterface $taskRepository)
    {
        $this->commandLoader = $commandLoader;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param \OpenApi\Annotations\OpenApi $api
     *
     * @return void
     */
    public function describe(OpenApi $api): void
    {
        $commandNames = $this->taskRepository->getTaskIds();
        sort($commandNames);

        foreach ($commandNames as $commandName) {
            $command = $this->commandLoader->get($commandName);

            $this->buildRoute(
                $api,
                $command,
                sprintf(static::RUN_TASK_ROUTE, $commandName),
                static::HTTP_METHOD,
                [$this->getTaskGroupTag($commandName)],
            );
        }
    }

    /**
     * @param string $taskName
     *
     * @return string
     */
    protected function getTaskGroupTag(string $taskName): string
    {
        preg_match('/^(?<group>[^:]*):/', $taskName, $matches);

        return isset($matches['group']) ? sprintf('Tasks: %s', $matches['group']) : 'Tasks';
    }
}
