<?php
namespace Sdk;

class Facade
{
    /**
     * @return array
     */
    public function getTaskDefinitions(): array
    {
        return $this->getFactory()->createSprykDefinitionDumper()->dump();
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
            ->loadSpryk($taskName);
    }
}
