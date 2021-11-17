<?php

namespace Sdk;

use Sdk\Style\StyleInterface;
use Sdk\Dto\TaskLogDto;
use Symfony\Component\Console\Input\InputInterface;
use Throwable;

class Facade
{
    /**
     * @var \Sdk\Factory
     */
    protected $factory;

    /**
     * @param string $taskName
     * @param array $options
     *
     * @return void
     */
    public function executeTask(string $taskName, array $options, StyleInterface $style): void
    {
        $this->getFactory()->createExecutor($taskName)->execute($options, $style);
    }

    /**
     * @param array $settings
     * @param \Sdk\Style\StyleInterface $style
     *
     * @return void
     */
    public function setSetting(array $settings, StyleInterface $style): void
    {
        $this->getFactory()->createSettings()->setSettings($settings, $style);
    }

    /**
     * @return array
     */
    public function getRequiredSettings(): array
    {
        return $this->getFactory()->createSettings()->getRequiredSettings();
    }

    /**
     * @return array
     */
    public function getTaskDefinitions(): array
    {
        return $this->getFactory()->createDefinitionDumper()->dump();
    }

    /**
     * @return string[]
     */
    public function dumpUniqueTaskPlaceholderNames(): array
    {
        return $this->getFactory()->createDefinitionDumper()->dumpUniqueTaskPlaceholderNames();
    }

    /**
     * @param string $taskName
     *
     * @throws \Sdk\Task\Exception\TaskTypeNotResolved
     *
     * @return array
     */
    public function getTaskDefinition(string $taskName): array
    {
        return $this->getFactory()->createDefinitionDumper()->dumpTaskDefinition($taskName);
    }

    /**
     * @param TaskLogDto $taskLogTransfer
     *
     * @return void
     */
    public function log(TaskLogDto $taskLogDto): void
    {
        $this->getFactory()->createLogger()->log($taskLogDto);
    }

    /**
     * @param Throwable $throwable
     * @param InputInterface $input
     *
     * @return TaskLogDto
     */
    public function mapExceptionToTaskLog(Throwable $throwable, InputInterface $input): TaskLogDto
    {
        return $this->getFactory()->createTaskLogMapper()->mapExceptionToTaskLog($throwable, $input);
    }

    /**
     * @param InputInterface $input
     *
     * @return TaskLogDto
     */
    public function mapExecutionToTaskLog(InputInterface $input): TaskLogDto
    {
        return $this->getFactory()->createTaskLogMapper()->mapExecutionToTaskLog($input);
    }

    /**
     * @return \Sdk\Factory
     */
    protected function getFactory(): Factory
    {
        if (!$this->factory) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }
}
