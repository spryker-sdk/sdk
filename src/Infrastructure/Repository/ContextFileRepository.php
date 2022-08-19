<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Application\Cache\ContextCacheStorageInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ContextSerializer;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Infrastructure\Exception\MissingContextFileException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ContextFileRepository implements ContextRepositoryInterface
{
    /**
     * @var string
     */
    protected const PROJECT_DIR_PATH = 'project_dir';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ContextSerializer
     */
    protected ContextSerializer $contextSerializer;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Cache\ContextCacheStorageInterface
     */
    protected ContextCacheStorageInterface $cacheStorage;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\ContextSerializer $contextSerializer
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Cache\ContextCacheStorageInterface $cacheStorage
     */
    public function __construct(
        ContextSerializer $contextSerializer,
        SettingRepositoryInterface $settingRepository,
        ContextCacheStorageInterface $cacheStorage
    ) {
        $this->contextSerializer = $contextSerializer;
        $this->settingRepository = $settingRepository;
        $this->cacheStorage = $cacheStorage;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function saveContext(ContextInterface $context): ContextInterface
    {
        $contextFilePath = $this->getContextFilePath($context->getName());

        file_put_contents($contextFilePath, $this->contextSerializer->serialize($context));

        return $context;
    }

    /**
     * @param string $name
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\MissingContextFileException
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function findByName(string $name): ContextInterface
    {
        $context = $this->cacheStorage->get($name);
        if ($context) {
            return $context;
        }

        $contextFilePath = $this->getContextFilePath($name);

        if (!is_readable($contextFilePath)) {
            throw new MissingContextFileException('Context file could not be found at ' . $contextFilePath);
        }

        $contextFileContent = file_get_contents($contextFilePath);

        if (!$contextFileContent) {
            throw new MissingContextFileException(sprintf('Context file %s could not be read', $contextFilePath));
        }

        $context = $this->contextSerializer->deserialize($contextFileContent);

        $this->cacheStorage->set($context->getName(), $context);

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function delete(ContextInterface $context): void
    {
        $this->cacheStorage->remove($context->getName());

        $contextFilePath = $this->getContextFilePath($context->getName());

        if (is_file($contextFilePath)) {
            unlink($contextFilePath);
        }
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function getLastSavedContextOrNew(): ContextInterface
    {
        $context = $this->cacheStorage->get(ContextCacheStorageInterface::KEY_LAST);
        if ($context) {
            return $context;
        }

        $context = new Context();
        $this->cacheStorage->set(ContextCacheStorageInterface::KEY_LAST, $context);

        return $context;
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    protected function getContextFilePath(string $filePath): string
    {
        if (preg_match('/\.context\.json$/', $filePath) != 1) {
            $filePath .= '.context.json';
        }

        if (preg_match('/^\//', $filePath) != 1) {
            $contextDir = (string)$this->settingRepository->getOneByPath(static::PROJECT_DIR_PATH)->getValues();

            $filePath = $contextDir . DIRECTORY_SEPARATOR . $filePath;
        }

        return $filePath;
    }
}
