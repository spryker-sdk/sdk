<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ConfigurableValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\UnresolvablePlaceholderException;
use SprykerSdk\Sdk\Core\Domain\Entity\PlaceholderInterface;

class PlaceholderResolver
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface
     */
    protected ValueResolverRegistryInterface $valueResolverRegistry;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface $valueResolverRegistry
     */
    public function __construct(
        ProjectSettingRepositoryInterface $settingRepository,
        ValueResolverRegistryInterface $valueResolverRegistry
    ) {
        $this->valueResolverRegistry = $valueResolverRegistry;
        $this->settingRepository = $settingRepository;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\PlaceholderInterface $placeholder
     *
     * @return mixed
     */
    public function resolve(PlaceholderInterface $placeholder): mixed
    {
            $valueResolverInstance = $this->getValueResolver($placeholder);

            $settingValues = [];

        foreach ($valueResolverInstance->getSettingPaths() as $settingPath) {
            $settingValues[$settingPath] = $this->settingRepository->findOneByPath($settingPath);
        }

            return $valueResolverInstance->getValue($settingValues, $placeholder->isOptional());
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\PlaceholderInterface $placeholder
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\UnresolvablePlaceholderException
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface
     */
    public function getValueResolver(PlaceholderInterface $placeholder): ValueResolverInterface
    {
        if ($this->valueResolverRegistry->has($placeholder->getValueResolver())) {
            $valueResolver = clone $this->valueResolverRegistry->get($placeholder->getValueResolver());

            if ($valueResolver instanceof ConfigurableValueResolverInterface) {
                $valueResolver->configure($placeholder->getConfiguration());
            }

            return $valueResolver;
        }

        throw new UnresolvablePlaceholderException('Placeholder not resolvable ' . $placeholder->getValueResolver());
    }
}
