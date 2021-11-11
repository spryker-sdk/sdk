<?php
namespace Sdk\Task\Configuration\Loader;

interface ConfigurationLoaderInterface
{
    /**
     * @param string $taskName
     *
     * @return array
     */
    public function loadTask(string $taskName): array;
}
