<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\ApiDoc;

use OpenApi\Annotations\OpenApi;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

class RunTaskDescriber extends BaseDescriber
{
    /**
     * @var string
     */
    protected const RUN_TASK_ROUTE = '/api/v1/task/%s';

    /**
     * @var array<string>
     */
    protected const OPERATION_TAGS = ['Tasks'];

    /**
     * @var string
     */
    protected const HTTP_METHOD = 'POST';

    /**
     * @var \Symfony\Component\Console\CommandLoader\CommandLoaderInterface
     */
    protected CommandLoaderInterface $commandLoader;

    /**
     * @param \Symfony\Component\Console\CommandLoader\CommandLoaderInterface $commandLoader
     */
    public function __construct(CommandLoaderInterface $commandLoader)
    {
        $this->commandLoader = $commandLoader;
    }

    /**
     * @param \OpenApi\Annotations\OpenApi $api
     *
     * @return void
     */
    public function describe(OpenApi $api): void
    {
        $commandNames = $this->commandLoader->getNames();
        sort($commandNames);

        foreach ($commandNames as $commandName) {
            $command = $this->commandLoader->get($commandName);

            $this->buildRoute(
                $api,
                $command,
                sprintf(static::RUN_TASK_ROUTE, $commandName),
                static::HTTP_METHOD,
                static::OPERATION_TAGS,
            );
        }
    }
}
