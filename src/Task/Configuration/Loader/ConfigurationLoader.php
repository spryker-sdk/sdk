<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\Configuration\Loader;

use Sdk\Task\Configuration\Validator\ConfigurationValidator;
use Sdk\Task\Exception\TaskDefinitionNotValid;
use Sdk\Task\Configuration\Finder\ConfigurationFinderInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var \Sdk\Task\Configuration\Finder\ConfigurationFinderInterface
     */
    protected $configurationFinder;

    /**
     * @var \Sdk\Task\Configuration\Validator\ConfigurationValidator
     */
    protected $configurationValidator;

    /**
     * @param \Sdk\Task\Configuration\Finder\ConfigurationFinderInterface $configurationFinder
     * @param \Sdk\Task\Configuration\Validator\ConfigurationValidator $configurationValidator
     */
    public function __construct(
        ConfigurationFinderInterface $configurationFinder,
        ConfigurationValidator $configurationValidator
    ) {
        $this->configurationFinder = $configurationFinder;
        $this->configurationValidator = $configurationValidator;
    }

    /**
     * @param string $taskName
     *
     * @return array
     */
    public function loadTask(string $taskName): array
    {
        $configuration = $this->configurationFinder->find($taskName);
        $configuration = Yaml::parse($configuration->getContents());

        $this->configurationValidate($configuration);

        return $configuration;
    }

    /**
     * @param array $configuration
     *
     * @throws \Sdk\Task\Exception\TaskDefinitionNotValid
     *
     * @return void
     */
    protected function configurationValidate(array $configuration): void
    {
        $validationErrorMessages = $this->configurationValidator->validate($configuration);

        if ($validationErrorMessages === []) {
            return;
        }

        throw new TaskDefinitionNotValid(implode(PHP_EOL, $validationErrorMessages));
    }
}
