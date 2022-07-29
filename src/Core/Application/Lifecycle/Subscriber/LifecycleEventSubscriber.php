<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Application\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\FileManagerInterface;
use SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\FileInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleEventDataInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

abstract class LifecycleEventSubscriber
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\FileManagerInterface
     */
    protected FileManagerInterface $fileManager;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\CommandExecutorInterface
     */
    protected CommandExecutorInterface $commandExecutor;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\FileManagerInterface $fileManager
     * @param \SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\CommandExecutorInterface $commandExecutor
     */
    public function __construct(
        FileManagerInterface $fileManager,
        PlaceholderResolver $placeholderResolver,
        CommandExecutorInterface $commandExecutor
    ) {
        $this->fileManager = $fileManager;
        $this->placeholderResolver = $placeholderResolver;
        $this->commandExecutor = $commandExecutor;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\FileInterface> $files
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function manageFiles(array $files, ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();

        $placeholdersKeys = array_map(function ($placeholder): string {
            return '~' . $placeholder . '~';
        }, array_keys($resolvedValues));

        $placeholdersValues = array_values($resolvedValues);

        foreach ($files as $file) {
            $path = preg_replace($placeholdersKeys, $placeholdersValues, $file->getPath());
            $content = preg_replace($placeholdersKeys, $placeholdersValues, $file->getContent());

            if (is_string($path) && is_string($content)) {
                $resolvedFile = new File($path, $content);

                $this->doManageFile($resolvedFile);
            }
        }
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function executeCommands(array $commands, ContextInterface $context): void
    {
        foreach ($commands as $command) {
            $this->commandExecutor->execute($command, $context);
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleEventDataInterface $eventData
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function createContext(LifecycleEventDataInterface $eventData, TaskInterface $task): ContextInterface
    {
        $context = new Context();
        $context->setTask($task);
        $resolvedValues = $this->placeholderResolver->resolvePlaceholders($eventData->getPlaceholders(), $context);

        $context->setResolvedValues($resolvedValues);

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\FileInterface $file
     *
     * @return void
     */
    abstract protected function doManageFile(FileInterface $file): void;
}
