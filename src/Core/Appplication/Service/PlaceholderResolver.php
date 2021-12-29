<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\UnresolvablePlaceholderException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\ValueResolver\ConfigurableValueResolverInterface;
use SprykerSdk\SdkContracts\ValueResolver\ValueResolverInterface;

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
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return mixed
     */
    public function resolve(PlaceholderInterface $placeholder, ContextInterface $context): mixed
    {
        $valueResolverInstance = $this->getValueResolver($placeholder);
        $settingValues = [];

        foreach ($valueResolverInstance->getSettingPaths() as $settingPath) {
            $setting = $this->settingRepository->findOneByPath($settingPath);

            if ($setting) {
                $settingValues[$settingPath] = $setting->getValues();
            }
        }

        return $valueResolverInstance->getValue(
            $context,
            $settingValues,
            $placeholder->isOptional(),
        );
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return array<string, mixed>
     */
    public function resolvePlaceholders(array $placeholders, ContextInterface $context): array
    {
        $resolvedValues = [];
        foreach ($placeholders as $placeholder) {
            $resolvedValues[$placeholder->getName()] = $this->resolve($placeholder, $context);
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
