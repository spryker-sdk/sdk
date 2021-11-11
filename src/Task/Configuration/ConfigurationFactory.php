<?php
namespace Sdk\Task\Configuration;

use Sdk\Config;
use Sdk\Factory;
use Sdk\Task\Configuration\Finder\ConfigurationFinder;
use Sdk\Task\Configuration\Finder\ConfigurationFinderInterface;
use Sdk\Task\Configuration\Loader\ConfigurationLoader;
use Sdk\Task\Configuration\Validator\ConfigurationValidator;
use Sdk\Task\Configuration\Validator\ConfigurationValidatorInterface;

class ConfigurationFactory
{
    /**
     * @var \Sdk\Config
     */
    protected $config;

    /**
     * @var \Sdk\Factory
     */
    protected $factory;

    /**
     * @param \Sdk\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface
     */
    public function createConfigurationLoader()
    {
        return new ConfigurationLoader(
            $this->createConfigurationFinder(),
            $this->createConfigurationValidator()
        );
    }

    /**
     * @return \Sdk\Task\Configuration\Validator\ConfigurationValidatorInterface
     */
    public function createConfigurationValidator(): ConfigurationValidatorInterface
    {
        return new ConfigurationValidator([]);
    }

    /**
     * @return \Sdk\Task\Configuration\Finder\ConfigurationFinderInterface
     */
    public function createConfigurationFinder(): ConfigurationFinderInterface
    {
        return new ConfigurationFinder($this->getFactory()->createTaskSettingReader()->read());
    }

    /**
     * @return \Sdk\Config
     */
    public function getConfig(): Config
    {
        if ($this->config === null) {
            $this->config = new Config();
        }

        return $this->config;
    }

    /**
     * @return \Sdk\Factory
     */
    public function getFactory(): Factory
    {
        if ($this->factory === null) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }
}
