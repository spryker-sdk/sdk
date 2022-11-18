<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\ApiDoc;

use OpenApi\Annotations\OpenApi;

class SdkCommandsDescriber extends BaseDescriber
{
    /**
     * @var array
     */
    protected const OPERATION_TAGS = ['SDK commands'];

    /**
     * @var string
     */
    protected const HTTP_METHOD = 'POST';

    /**
     * @var iterable<\Symfony\Component\Console\Command\Command>
     */
    protected iterable $commands;

    /**
     * @param iterable<\Symfony\Component\Console\Command\Command> $commands
     */
    public function __construct(iterable $commands)
    {
        $this->commands = $commands;
    }

    /**
     * @param \OpenApi\Annotations\OpenApi $api
     *
     * @return void
     */
    public function describe(OpenApi $api)
    {
        foreach ($this->commands as $command) {
            $route = str_replace(':', '-', $command->getName());

            $this->buildRoute(
                $api,
                $command,
                sprintf('/api/v1/%s', $route),
                static::HTTP_METHOD,
                static::OPERATION_TAGS,
            );
        }
    }
}
