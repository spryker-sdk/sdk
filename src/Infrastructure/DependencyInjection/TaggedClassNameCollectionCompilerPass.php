<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\DependencyInjection;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TaggedClassNameCollectionCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    protected const COLLECTION_TAG = 'tagged_class_name_collection';

    /**
     * @var string
     */
    protected const TARGET_TAG = 'target_tag';

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $taggedServiceIds = $container->findTaggedServiceIds(static::COLLECTION_TAG);

        foreach ($taggedServiceIds as $collectionServiceId => $tags) {
            $this->processCollectionService($collectionServiceId, $tags, $container);
        }
    }

    /**
     * @param string $collectionServiceId
     * @param array<array<string>> $tags
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function processCollectionService(string $collectionServiceId, array $tags, ContainerBuilder $container): void
    {
        if (count($tags) !== 1) {
            throw new InvalidArgumentException(sprintf('Only one tag `%s` is allowed', static::COLLECTION_TAG));
        }

        if (!isset($tags[0][static::TARGET_TAG])) {
            throw new InvalidArgumentException(
                sprintf('Tag `%s` should be set with `%s` tag', static::TARGET_TAG, static::COLLECTION_TAG),
            );
        }

        $targetTag = $tags[0][static::TARGET_TAG];

        $targetServiceIds = $container->findTaggedServiceIds($targetTag);

        $targetServiceClasses = $this->getTargetTagServicesClasses($targetServiceIds, $container);

        $serviceDefinition = $container->getDefinition($collectionServiceId);

        $serviceDefinition->setArguments([$targetServiceClasses]);
    }

    /**
     * @param array $targetServiceIds
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return array<string>
     */
    protected function getTargetTagServicesClasses(array $targetServiceIds, ContainerBuilder $container): array
    {
        $serviceClasses = [];

        foreach ($targetServiceIds as $serviceId => $tags) {
            $class = $container->getDefinition($serviceId)->getClass();

            if ($class === null) {
                continue;
            }

            $serviceClasses[] = $class;
        }

        return $serviceClasses;
    }
}
