<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\UnresolvablePlaceholderException;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConfigurableValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\PlaceholderInterface;

class PlaceholderResolver
{
    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface $valueResolverRegistry
     */
    public function __construct(
        protected ProjectSettingRepositoryInterface $settingRepository,
        protected ValueResolverRegistryInterface $valueResolverRegistry,
    ) {
    }

    /**
     * @param PlaceholderInterface $placeholder
     *
     * @return mixed
     */
    public function resolve(PlaceholderInterface $placeholder): mixed
    {
            $valueResolverInstance = $this->getValueResolver($placeholder);

            $settingValues = [];
            $settingValues[$valueResolverInstance->getAlias()] = $this->settingRepository->findOneByPath($valueResolverInstance->getAlias());

            foreach ($valueResolverInstance->getSettingPaths() as $settingPath) {
                // @TODO I guess we need to take this from .ssdk
                $settingValues[$settingPath] = $this->settingRepository->findOneByPath($settingPath);
            }

            return $valueResolverInstance->getValue($settingValues, $placeholder->isOptional());
    }

    /**
     * @param PlaceholderInterface $placeholder
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
