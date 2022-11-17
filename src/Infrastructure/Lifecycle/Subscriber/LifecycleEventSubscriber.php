<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Application\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\Sdk\Core\Domain\Entity\FileInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface;
use SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem;
use SprykerSdk\SdkContracts\Entity\ContextInterface as ContractContextInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

abstract class LifecycleEventSubscriber
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\CommandExecutorInterface
     */
    protected CommandExecutorInterface $commandExecutor;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem $filesystem
     * @param \SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\CommandExecutorInterface $commandExecutor
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface $contextFactory
     */
    public function __construct(
        Filesystem $filesystem,
        PlaceholderResolver $placeholderResolver,
        CommandExecutorInterface $commandExecutor,
        ContextFactoryInterface $contextFactory
    ) {
        $this->filesystem = $filesystem;
        $this->placeholderResolver = $placeholderResolver;
        $this->commandExecutor = $commandExecutor;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\FileInterface> $files
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function manageFiles(array $files, ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();

        $placeholdersKeys = array_map(function ($placeholder): string {
            return '#' . $placeholder . '#';
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
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
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
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface $eventData
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    protected function createContext(LifecycleEventDataInterface $eventData, TaskInterface $task): ContractContextInterface
    {
        $context = $this->contextFactory->getContext();
        $context->setTask($task);
        $resolvedValues = $this->placeholderResolver->resolvePlaceholders($eventData->getPlaceholders(), $context);

        $context->setResolvedValues($resolvedValues);

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\FileInterface $file
     *
     * @return void
     */
    abstract protected function doManageFile(FileInterface $file): void;
}
