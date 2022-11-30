<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface;
use SprykerSdk\Sdk\Core\Application\Service\ContextSerializer;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\MissingContextFileException;
use SprykerSdk\SdkContracts\Enum\Setting;

class ContextFileRepository implements ContextRepositoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ContextSerializer
     */
    protected ContextSerializer $contextSerializer;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface
     */
    protected SettingFetcherInterface $settingFetcher;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\ContextSerializer $contextSerializer
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface $settingFetcher
     */
    public function __construct(
        ContextSerializer $contextSerializer,
        SettingFetcherInterface $settingFetcher
    ) {
        $this->contextSerializer = $contextSerializer;
        $this->settingFetcher = $settingFetcher;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
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
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    public function findByName(string $name): ContextInterface
    {
        $contextFilePath = $this->getContextFilePath($name);

        if (!is_readable($contextFilePath)) {
            throw new MissingContextFileException('Context file could not be found at ' . $contextFilePath);
        }

        $contextFileContent = file_get_contents($contextFilePath);

        if (!$contextFileContent) {
            throw new MissingContextFileException(sprintf('Context file %s could not be read', $contextFilePath));
        }

        return $this->contextSerializer->deserialize($contextFileContent);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
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
            $contextDir = (string)$this->settingFetcher->getOneByPath(Setting::PATH_PROJECT_DIR)->getValues();

            $filePath = $contextDir . DIRECTORY_SEPARATOR . $filePath;
        }

        return $filePath;
    }
}
