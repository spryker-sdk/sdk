<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\ApiDoc;

use OpenApi\Annotations\OpenApi;
use Symfony\Component\Console\Application;

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
     * @var array<string>
     */
    protected const SDK_COMMAND_TO_ROUTE = [
        'sdk:init:project' => 'sdk-init-project',
        'sdk:init:sdk' => 'sdk-init-sdk',
        'sdk:update:all' => 'sdk-update-sdk',
    ];

    /**
     * @var \Symfony\Component\Console\Application
     */
    protected Application $application;

    /**
     * @param \Symfony\Component\Console\Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param \OpenApi\Annotations\OpenApi $api
     *
     * @return void
     */
    public function describe(OpenApi $api)
    {
        foreach (static::SDK_COMMAND_TO_ROUTE as $commandName => $route) {
            $command = $this->application->get($commandName);

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
