<?php

namespace Sdk;

use Sdk\Logger\Logger;
use Sdk\Logger\LoggerFactory;
use Sdk\Setting\Reader\ReportUsageStatisticsReader;
use Sdk\Setting\Reader\SettingReaderInterface;
use Sdk\Setting\Reader\TaskSettingReader;
use Sdk\Setting\Reader\ValueResolverSettingReader;
use Sdk\Setting\Setting;
use Sdk\Setting\SettingInterface;
use Sdk\Style\Style;
use Sdk\Style\StyleInterface;
use Sdk\Task\Configuration\ConfigurationFactory;
use Sdk\Task\Dumper\DefinitionDumper;
use Sdk\Task\Dumper\DefinitionDumperInterface;
use Sdk\Task\Dumper\Finder\DefinitionFinder;
use Sdk\Task\Dumper\Finder\DefinitionFinderInterface;
use Sdk\Task\Executor\TaskExecutor;
use Sdk\Task\Executor\TaskExecutorInterface;
use Sdk\Task\StrategyResolver;
use Sdk\Task\StrategyResolverInterface;
use Sdk\Task\TypeStrategy\LocalCliTypeStrategy;
use Sdk\Task\TypeStrategy\TaskSetTypeStrategy;
use Sdk\Task\TypeStrategy\TypeStrategyInterface;
use Sdk\Task\ValueResolver\ValueResolver;
use Sdk\Task\ValueResolver\ValueResolverInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Factory
{
    /**
     * @var \Sdk\Config
     */
    protected $config;

    /**
     * @param string $taskName
     *
     * @return \Sdk\Task\Executor\TaskExecutorInterface
     */
    public function createExecutor(string $taskName): TaskExecutorInterface
    {
        return new TaskExecutor(
            $this->createTypeStrategy($taskName),
            $this->createSettings(),
            $this->createValueResolver()
        );
    }

    /**
     * @param string $taskName
     *
     * @throws \Sdk\Task\Exception\TaskTypeNotResolved
     *
     * @return \Sdk\Task\TypeStrategy\TypeStrategyInterface
     */
    public function createTypeStrategy(string $taskName): TypeStrategyInterface
    {
        return $this->createStrategyResolver()
            ->resolve(
                $this->createConfigurationFactory()
            ->createConfigurationLoader()
            ->loadTask($taskName)
            );
    }

    /**
     * @return \Sdk\Task\StrategyResolverInterface
     */
    public function createStrategyResolver(): StrategyResolverInterface
    {
        return new StrategyResolver([
            $this->createLocalCliTypeStrategy(),
            $this->createTaskSetTypeStrategy()
        ]);
    }

    /**
     * @return \Sdk\Task\ValueResolver\ValueResolverInterface
     */
    protected function createValueResolver(): ValueResolverInterface
    {
        return new ValueResolver(
            $this->createValueResolverSettingReader()->read(),
            $this->createSettings()
        );
    }

    /**
     * @return \Sdk\Setting\Reader\SettingReaderInterface
     */
    public function createValueResolverSettingReader(): SettingReaderInterface
    {
        return new ValueResolverSettingReader($this->getConfig()->getRootDirectory(), $this->createSettings());
    }

    /**
     * @return \Sdk\Setting\Reader\SettingReaderInterface
     */
    public function createTaskSettingReader(): SettingReaderInterface
    {
        return new TaskSettingReader($this->getConfig()->getRootDirectory(), $this->createSettings());
    }

    /**
     * @return \Sdk\Setting\SettingInterface
     */
    public function createSettings(): SettingInterface
    {
        return new Setting(
            $this->getConfig()->getSettingDefinitionDirectories(),
            $this->getConfig()->getSettingFilePath()
        );
    }

    /**
     * @return \Sdk\Task\TypeStrategy\TaskSetTypeStrategy
     */
    public function createTaskSetTypeStrategy(): TaskSetTypeStrategy
    {
        return new TaskSetTypeStrategy($this->createConfigurationFactory()->createConfigurationLoader());
    }

    /**
     * @return \Sdk\Task\TypeStrategy\LocalCliTypeStrategy
     */
    public function createLocalCliTypeStrategy(): LocalCliTypeStrategy
    {
        return new LocalCliTypeStrategy();
    }

    /**
     * @return \Sdk\Task\Dumper\DefinitionDumperInterface
     */
    public function createDefinitionDumper(): DefinitionDumperInterface
    {
        return new DefinitionDumper(
            $this->createDefinitionFinder(),
            $this->createConfigurationFactory()->createConfigurationLoader(),
            $this->createValueResolver(),
            $this->createStrategyResolver()
        );
    }

    /**
     * @return \Sdk\Task\Dumper\Finder\DefinitionFinderInterface
     */
    public function createDefinitionFinder(): DefinitionFinderInterface
    {
        return new DefinitionFinder(
            $this->createTaskSettingReader()->read()
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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Sdk\Style\StyleInterface
     */
    public function createStyle(InputInterface $input, OutputInterface $output): StyleInterface
    {
        return new Style($input, $output);
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

    /**
     * @return \Sdk\Setting\Reader\SettingReaderInterface
     */
    public function createReportUsageStatisticsReader(): SettingReaderInterface
    {
        return new ReportUsageStatisticsReader(
            $this->getConfig()->getRootDirectory(),
            $this->createSettings()
        );
    }

    /**
     * @return \Sdk\Logger\Logger
     */
    public function createLogger(): Logger
    {
        return $this
            ->createLoggerFactory()
            ->createLogger(
                $this->getConfig()->getLoggerFilePath(),
                $this->createReportUsageStatisticsReader()->read(),
            );
    }

    /**
     * @return \Sdk\Logger\LoggerFactory
     */
    public function createLoggerFactory(): LoggerFactory
    {
        return new LoggerFactory();
    }
}
