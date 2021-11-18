<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\UnresolvablePlaceholderException;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConfigurableValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;

class PlaceholderResolver
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface $valueResolverRegistry
     */
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
        protected ValueResolverRegistryInterface $valueResolverRegistry,
    ) {
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Placeholder $placeholder
     *
     * @return mixed
     */
    public function resolve(Placeholder $placeholder): mixed
    {
            $valueResolverInstance = $this->getValueResolver($placeholder);

            $settingValues = [];

            foreach ($valueResolverInstance->getSettingPaths() as $settingPath) {
                $settingValues[$settingPath] = $this->settingRepository->findOneByPath($settingPath);
            }

            return $valueResolverInstance->getValue($settingValues);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Placeholder $placeholder
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface
     */
    public function getValueResolver(Placeholder $placeholder): ValueResolverInterface
    {
        if ($this->valueResolverRegistry->has($placeholder->valueResolver)) {
            $valueResolver = clone $this->valueResolverRegistry->get($placeholder->valueResolver);

            if ($valueResolver instanceof ConfigurableValueResolverInterface) {
                $valueResolver->configure($placeholder->configuration);
            }

            return $valueResolver;
        }

        throw new UnresolvablePlaceholderException('Placeholder not resolvable ' . $placeholder->valueResolver);
    }
}