<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Contracts\Entity\FileInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\FileManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\File;

abstract class LifecycleEventSubscriber
{
    protected FileManagerInterface $fileManager;

    protected PlaceholderResolver $placeholderResolver;

    protected CommandExecutorInterface $commandExecutor;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\FileManagerInterface $fileManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface $commandExecutor
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
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\FileInterface> $files
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $placeholders
     *
     * @return void
     */
    protected function manageFiles(array $files, array $placeholders): void
    {
        $resolvedValues = $this->placeholderResolver->resolvePlaceholders($placeholders);

        $placeholdersKeys = array_map(function (mixed $placeholder): string {
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface $file
     *
     * @return void
     */
    abstract protected function doManageFile(FileInterface $file): void;
}
