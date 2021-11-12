<?php
namespace Sdk;

use Sdk\Style\StyleInterface;

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
     * @return \Sdk\Factory
     */
    protected function getFactory()
    {
        if (!$this->factory) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }
}
