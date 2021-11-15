<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Port\ConfigurableValueResolver;
use SprykerSdk\Sdk\Core\Appplication\Port\ValueResolverInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;

class PlaceholderResolver
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface $settingRepository
     * @param array<\SprykerSdk\Sdk\Core\Appplication\Port\ValueResolverInterface> $valueResolver
     */
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
        protected iterable $valueResolver
    ) {
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Placeholder $placeholder
     *
     * @return mixed
     */
    public function resolve(Placeholder $placeholder): mixed
    {
        foreach ($this->valueResolver as $valueResolver) {

            $valueResolverInstance = $this->getValueResolver($valueResolver, $placeholder);

            $settingValues = [];

            foreach ($valueResolverInstance->getSettingPaths() as $settingPath) {
                $settingValues[$settingPath] = $this->settingRepository->findByPath($settingPath);
            }

            if ($valueResolverInstance instanceof ConfigurableValueResolver) {
                $valueResolverInstance->configure($placeholder->configuration);
            }

            return $valueResolverInstance->getValue($settingValues);

        }

        throw new \RuntimeException('Could not resolve value for ' . $placeholder->name);
    }

    /**
     * @param mixed $valueResolver
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Placeholder $placeholder
     */
    protected function getValueResolver(mixed $valueResolver, Placeholder $placeholder): ValueResolverInterface
    {
        if ($valueResolver->getId() === $placeholder->valueResolver) {
            return $valueResolver;
        }

        if (class_exists($placeholder->valueResolver)) {
            return new $placeholder->valueResolver();
        }

        throw new \RuntimeException('Placeholder not resolvable ' . $placeholder->valueResolver);
    }
}