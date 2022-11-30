<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractInitCommand extends Command
{
    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var string
     */
    protected string $settingsPath;

    /**
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $settingsPath
     * @param string|null $commandName
     */
    public function __construct(Yaml $yamlParser, string $settingsPath, ?string $commandName = null)
    {
        $this->yamlParser = $yamlParser;
        $this->settingsPath = $settingsPath;
        parent::__construct($commandName);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $settings = $this->yamlParser::parseFile($this->settingsPath, $this->yamlParser::PARSE_CONSTANT)['settings'] ?? [];

        foreach ($settings as $settingData) {
            if (isset($settingData['values']) || isset($settingData['initializer'])) {
                continue;
            }

            $this->addOption(
                $settingData['path'],
                null,
                $settingData['strategy'] === 'merge' ? InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY : InputOption::VALUE_REQUIRED,
                $settingData['initialization_description'],
            );
        }
    }
}
