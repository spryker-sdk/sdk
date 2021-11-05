<?php
namespace Sdk;

class Facade
{
    /**
     * @var \Sdk\Factory
     */
    protected $factory;

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
     * @return array
     */
    public function getTaskDefinition(string $taskName): array
    {
        return $this->getFactory()
            ->createConfigurationFactory()
            ->createConfigurationLoader()
            ->loadTask($taskName);
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
