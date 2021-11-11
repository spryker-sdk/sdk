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
     * @param string $taskName
     *
     * @throws \Sdk\Task\Exception\TaskTypeNotResolved
     *
     * @return array
     */
    public function getTaskDefinition(string $taskName): array
    {
        return $this->getFactory()->createTypeStrategy($taskName)->extract();
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
