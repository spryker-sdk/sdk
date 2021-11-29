<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Contracts\Entity\FileInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\FileManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\File;

abstract class LifecycleEventSubscriber
{
    protected FileManagerInterface $fileManager;

    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var iterable<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface> $commandRunners
     */
    protected iterable $commandRunners;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\FileManagerInterface $fileCreator
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param iterable<\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface> $commandRunners
     */
    public function __construct(
        FileManagerInterface $fileManager,
        PlaceholderResolver $placeholderResolver,
        iterable $commandRunners
    ) {
        $this->fileManager = $fileManager;
        $this->placeholderResolver = $placeholderResolver;
        $this->commandRunners = $commandRunners;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface[] $commands
     * @param array<string, mixed> $resolvedValues
     *
     * @return void
     */
    protected function executeCommands(array $commands, array $resolvedValues): void
    {
        foreach ($commands as $command) {
            foreach ($this->commandRunners as $commandRunner) {
                if ($commandRunner->canHandle($command)) {
                    $result = $commandRunner->execute($command, $resolvedValues);

                    if ($result !== 0 && $command->hasStopOnError()) {
                        return;
                    }
                }
            }
        }
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface[] $placeholders
     *
     * @return array<string, mixed>
     */
    protected function resolvePlaceholders(array $placeholders): array
    {
        $resolvedValues = [];
        foreach ($placeholders as $placeholder) {
            $resolvedValues[$placeholder->getName()] = $this->placeholderResolver->resolve($placeholder);
        }

        return $resolvedValues;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface[] $files
     * @param array<string, mixed> $resolvedValues
     *
     * @return void
     */
    protected function manageFiles(array $files, array $resolvedValues): void
    {
        $placeholdersKeys = array_map(function (mixed $placeholder): string {
            return '~' . $placeholder . '~';
        }, array_keys($resolvedValues));

        $placeholdersValues = array_values($resolvedValues);

        foreach ($files as $file) {
            $path = preg_replace($placeholdersKeys, $placeholdersValues, $file->getPath());
            $content = preg_replace($placeholdersKeys, $placeholdersValues, $file->getContent());

            $resolvedFile = new File($path, $content);

            $this->doManageFile($resolvedFile);
        }
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface $file
     *
     * @return void
     */
    abstract protected function doManageFile(FileInterface $file): void;
}
