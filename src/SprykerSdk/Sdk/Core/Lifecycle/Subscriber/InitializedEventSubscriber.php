<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Appplication\Dependency\FileCreatorInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\Sdk\Core\Lifecycle\Event\InitializedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitializedEventSubscriber implements EventSubscriberInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\FileCreatorInterface $fileCreator
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param iterable<\SprykerSdk\Sdk\Core\Appplication\Dependency\CommandRunnerInterface> $commandRunners
     */
    public function __construct(
        protected FileCreatorInterface $fileCreator,
        protected PlaceholderResolver $placeholderResolver,
        protected iterable $commandRunners
    ) {
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Lifecycle\Event\InitializedEvent $event
     *
     * @return void
     */
    public function onInitializedEvent(InitializedEvent $event): void
    {
        $initialized = $event->getTask()->getLifecycle()?->getInitialized();
        if ($initialized === null) {
            return;
        }

        $resolvedPlaceholders = $this->resolvePlaceholders($initialized->getPlaceholders());

        $this->createFiles($initialized->getFiles(), $resolvedPlaceholders);
        $this->executeCommands($initialized->getCommands(), $resolvedPlaceholders);
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface[] $files
     * @param array<string, mixed> $resolvedValues
     *
     * @return void
     */
    protected function createFiles(array $files, array $resolvedValues): void
    {
        $placeholdersKeys = array_map(function (mixed $placeholder): string {
            return '~' . $placeholder . '~';
        }, array_keys($resolvedValues));

        $placeholdersValues = array_values($resolvedValues);

        foreach ($files as $file) {
            $path = preg_replace($placeholdersKeys, $placeholdersValues, $file->getPath());
            $content = preg_replace($placeholdersKeys, $placeholdersValues, $file->getContent());

            $resolvedFile = new File($path, $content);

            $this->fileCreator->create($resolvedFile);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InitializedEvent::NAME => 'onInitializedEvent',
        ];
    }
}
