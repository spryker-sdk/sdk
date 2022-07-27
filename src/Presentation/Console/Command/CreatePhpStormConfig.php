<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\ConfigManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePhpStormConfig extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'sdk:php:create-phpstorm-config';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Puts PhpStorm XML config to `.idea/` directory to make spryker-sdk available as the tool';

    protected ConfigManagerInterface $configManager;

    /**
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\ConfigManagerInterface $configManager
     */
    public function __construct(ConfigManagerInterface $configManager)
    {
        parent::__construct(static::NAME);
        $this->configManager = $configManager;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return (int)$this->configManager->createXmlFile();
    }
}
