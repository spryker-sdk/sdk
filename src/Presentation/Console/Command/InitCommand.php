<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use SprykerSdk\Sdk\Infrastructure\Service\Initializer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class InitCommand extends AbstractInitCommand
{
    /**
     * @var string
     */
    public const NAME = 'sdk:init:hidden-sdk';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Initializer
     */
    protected Initializer $initializerService;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Initializer $initializerService
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $settingsPath
     */
    public function __construct(
        Initializer $initializerService,
        Yaml $yamlParser,
        string $settingsPath
    ) {
        $this->initializerService = $initializerService;
        parent::__construct($yamlParser, $settingsPath, static::NAME);

        $this->setHidden(true);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initializerService->initialize($input->getOptions());

        return static::SUCCESS;
    }
}
