<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ContextSerializer;
use SprykerSdk\Sdk\Infrastructure\Exception\MissingContextFileException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ContextFileRepository implements ContextRepositoryInterface
{
    /**
     * @var string
     */
    protected const CONTEXT_DIR_PATH = 'context_dir';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ContextSerializer
     */
    protected ContextSerializer $contextSerializer;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ContextSerializer $contextSerializer
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(ContextSerializer $contextSerializer, SettingRepositoryInterface $settingRepository)
    {
        $this->contextSerializer = $contextSerializer;
        $this->settingRepository = $settingRepository;
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
        $contextFilePath = $this->getContextFilePath($name);

        if (!is_readable($contextFilePath)) {
            throw new MissingContextFileException('Context file could not be found at ' . $contextFilePath);
        }

        $contextFileContent = file_get_contents($contextFilePath);

        if (!$contextFileContent) {
            throw new MissingContextFileException(sprintf('Context file %s could not be read', $contextFilePath,));
        }

        return $this->contextSerializer->deserialize($contextFileContent);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function delete(ContextInterface $context): void
    {
        $contextFilePath = $this->getContextFilePath($context->getName());

        if (is_file($contextFilePath)) {
            unlink($contextFilePath);
        }
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
            $contextDir = (string)$this->settingRepository->getOneByPath(static::CONTEXT_DIR_PATH)->getValues();

            $filePath = $contextDir . DIRECTORY_SEPARATOR . $filePath;
        }

        return $filePath;
    }
}
