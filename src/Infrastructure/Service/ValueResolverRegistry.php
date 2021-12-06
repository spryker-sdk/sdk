<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Composer\Autoload\ClassLoader;
use SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface;
use SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;

class ValueResolverRegistry implements ValueResolverRegistryInterface
{
    protected bool $isInitialized = false;

    /**
     * @var array<string, \SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface>
     */
    protected array $valueResolvers = [];

    /**
     * @var array<string, \SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface>
     */
    protected array $valueResolversClasses = [];

    /**
     * @var \Composer\Autoload\ClassLoader
     */
    protected ClassLoader $classLoader;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface $valueReceiver
     * @param iterable<\SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface> $valueResolverServices
     * @param string $sdkBasePath
     */
    protected SettingRepositoryInterface $settingRepository;

    protected ValueReceiverInterface $valueReceiver;

    protected string $sdkBasePath;

    /**
     * @var iterable<\SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface>
     */
    protected iterable $valueResolverServices;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\AutoloaderService
     */
    protected AutoloaderService $autoloaderService;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface $valueReceiver
     * @param iterable<\SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface> $valueResolverServices
     * @param \SprykerSdk\Sdk\Infrastructure\Service\AutoloaderService $autoloaderService
     * @param string $sdkBasePath
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        ValueReceiverInterface $valueReceiver,
        iterable $valueResolverServices,
        AutoloaderService $autoloaderService,
        string $sdkBasePath
    ) {
        $this->valueResolverServices = $valueResolverServices;
        $this->valueReceiver = $valueReceiver;
        $this->settingRepository = $settingRepository;
        $this->autoloaderService = $autoloaderService;
        $this->sdkBasePath = $sdkBasePath;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        $this->loadValueResolvers();

        if ($this->hasId($id)) {
            return true;
        }

        return $this->hasClass($id);
    }

    /**
     * @param string $id
     *
     * @return \SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface|null
     */
    public function get(string $id): ?ValueResolverInterface
    {
        if ($this->hasId($id)) {
            return $this->valueResolvers[$id];
        }

        if ($this->hasClass($id)) {
            return $this->valueResolversClasses[$id];
        }

        return null;
    }

    /**
     * @return void
     */
    protected function loadValueResolvers()
    {
        if ($this->isInitialized) {
            return;
        }

        $this->isInitialized = true;

        $this->loadValueResolverServices();
        $this->loadValueResolversFromFiles();
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    protected function hasId(string $id): bool
    {
        return array_key_exists($id, $this->valueResolvers);
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    protected function hasClass(string $id): bool
    {
        return array_key_exists($id, $this->valueResolversClasses);
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return array<string>
     */
    protected function getValueResolverDirectories(): array
    {
        $paths = $this->settingRepository->findOneByPath('value_resolver_dirs');

        if (!$paths) {
            throw new MissingSettingException('Setting value_resolver_dirs is missing');
        }

        return $paths->getValues();
    }

    /**
     * @return void
     */
    protected function loadValueResolverServices(): void
    {
        foreach ($this->valueResolverServices as $valueResolverService) {
            $this->valueResolvers[$valueResolverService->getId()] = $valueResolverService;
            $this->valueResolversClasses[get_class($valueResolverService)] = $valueResolverService;
        }
    }

    /**
     * @return void
     */
    protected function loadValueResolversFromFiles(): void
    {
        $this->autoloaderService->loadClassesFromDirectory(
            $this->getValueResolverDirectories(),
            '*ValueResolver.php',
            function (string $loadableClassName) {
                $this->loadValueResolver($loadableClassName);
            },
        );
    }

    /**
     * @param string $loadableClassName
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException
     *
     * @return void
     */
    protected function loadValueResolver(string $loadableClassName): void
    {
        //value resolver might already be registered as service
        if (array_key_exists($loadableClassName, $this->valueResolversClasses)) {
            return;
        }

        $valueResolver = new $loadableClassName($this->valueReceiver);

        if (!$valueResolver instanceof ValueResolverInterface) {
            throw new InvalidTypeException(sprintf('Value resolver (%s) must implement %s', $valueResolver::class, ValueResolverInterface::class));
        }

        $this->valueResolvers[$valueResolver->getId()] = $valueResolver;
        $this->valueResolversClasses[get_class($valueResolver)] = $valueResolver;
    }
}
