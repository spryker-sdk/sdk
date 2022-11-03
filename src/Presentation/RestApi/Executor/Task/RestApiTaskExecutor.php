<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Executor\Task;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class RestApiTaskExecutor
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Console\Application
     */
    protected Application $application;

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Console\Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;

        $this->configureApplication();
    }

    /**
     * @param array $arguments
     *
     * @return string
     */
    public function execute(array $arguments): string
    {
        $arguments[] = '--no-interaction';
        $input = new ArrayInput($arguments);

        $output = new BufferedOutput();
        $this->application->run($input, $output);

        return $output->fetch();
    }

    /**
     * @return void
     */
    protected function configureApplication(): void
    {
        $this->application->setAutoExit(false);
    }
}
