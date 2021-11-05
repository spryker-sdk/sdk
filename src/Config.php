<?php

namespace Sdk;

class Config
{
    /**
     * @return string[]
     */
    public function getTasksDirectories(): array
    {
        return $this->buildDirectoryList('tasks');
    }

    /**
     * @param string|null $subDirectory
     *
     * @return string[]
     */
    protected function buildDirectoryList(?string $subDirectory = null): array
    {
        $subDirectory = (is_string($subDirectory)) ? $subDirectory . DIRECTORY_SEPARATOR : DIRECTORY_SEPARATOR;

        $directories = [];
        $taskDirectory = realpath($this->getRootDirectory() . 'config/' . $subDirectory);

        if ($taskDirectory !== false) {
            $directories[] = $taskDirectory . DIRECTORY_SEPARATOR;
        }

        return $directories;
    }

    /**
     * @return string
     */
    public function getRootDirectory(): string
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR;
    }
}
