<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\UnresolvablePlaceholderException;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\ValueResolver\ConfigurableValueResolverInterface;
use SprykerSdk\SdkContracts\ValueResolver\ValueResolverInterface;

class PlaceholderResolver
{
    /**
     * @var array
     */
    protected $resolvedValues = [];

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
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return mixed
     */
    public function resolve(PlaceholderInterface $placeholder): mixed
    {
        $valueResolverInstance = $this->getValueResolver($placeholder);
        $settingValues = [];
        foreach ($valueResolverInstance->getSettingPaths() as $settingPath) {
            $setting = $this->settingRepository->findOneByPath($settingPath);

            if ($setting) {
                $settingValues[$settingPath] = $setting->getValues();
            }
        }

        if (empty($this->resolvedValues[$valueResolverInstance->getAlias()])) {
            $this->resolvedValues[$valueResolverInstance->getAlias()] = $valueResolverInstance->getValue($settingValues, $placeholder->isOptional(), $this->resolvedValues);
        }

        return $this->resolvedValues[$valueResolverInstance->getAlias()];
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     *
     * @return array<string, mixed>
     */
    public function resolvePlaceholders(array $placeholders): array
    {
        $resolvedValues = [];
        foreach ($placeholders as $placeholder) {
            $resolvedValues[$placeholder->getName()] = $this->resolve($placeholder);
        }

        return $resolvedValues;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\UnresolvablePlaceholderException
     *
     * @return \SprykerSdk\SdkContracts\ValueResolver\ValueResolverInterface
     */
    public function getValueResolver(PlaceholderInterface $placeholder): ValueResolverInterface
    {
        if ($this->valueResolverRegistry->has($placeholder->getValueResolver())) {
            /** @var \SprykerSdk\SdkContracts\ValueResolver\ValueResolverInterface $valueResolverPrototype */
            $valueResolverPrototype = $this->valueResolverRegistry->get($placeholder->getValueResolver());

            $valueResolver = clone $valueResolverPrototype;

            if ($valueResolver instanceof ConfigurableValueResolverInterface) {
                $valueResolver->configure($placeholder->getConfiguration());
            }

            return $valueResolver;
        }

        throw new UnresolvablePlaceholderException('Placeholder not resolvable ' . $placeholder->getValueResolver());
    }
}
