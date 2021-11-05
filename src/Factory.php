<?php
namespace Sdk;

use Sdk\Task\Configuration\ConfigurationFactory;
use Sdk\Task\Dumper\DefinitionDumper;
use Sdk\Task\Dumper\DefinitionDumperInterface;
use Sdk\Task\Dumper\Finder\DefinitionFinder;
use Sdk\Task\Dumper\Finder\DefinitionFinderInterface;

class Factory
{
    /**
     * @var \Sdk\Config
     */
    protected $config;

    public function createTaskDefinitionDumper() {

    }

    /**
     * @return \Sdk\Task\Dumper\DefinitionDumperInterface
     */
    public function createDefinitionDumper(): DefinitionDumperInterface
    {
        return new DefinitionDumper(
            $this->createDefinitionFinder(),
            $this->createConfigurationFactory()->createConfigurationLoader()
        );
    }

    /**
     * @return \Sdk\Task\Dumper\Finder\DefinitionFinderInterface
     */
    public function createDefinitionFinder(): DefinitionFinderInterface
    {
        return new DefinitionFinder(
            $this->getConfig()->getTasksDirectories()
        );
    }

    /**
     * @return \Sdk\Task\Configuration\ConfigurationFactory
     */
    public function createConfigurationFactory(): ConfigurationFactory
    {
        return new ConfigurationFactory($this->getConfig());
    }

    /**
     * @return \Sdk\Config
     */
    public function getConfig(): Config
    {
        if (!$this->config) {
            $this->config = new Config();
        }

        return $this->config;
    }
}
