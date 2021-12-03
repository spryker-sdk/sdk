<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Contracts\Entity\ContextInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ContextSerializer;
use SprykerSdk\Sdk\Infrastructure\Exception\MissingContextFileException;

class ContextFileRepository implements ContextRepositoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ContextSerializer
     */
    protected ContextSerializer $contextSerializer;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ContextSerializer $contextSerializer
     */
    public function __construct(ContextSerializer $contextSerializer)
    {
        $this->contextSerializer = $contextSerializer;
    }

    /**
     * @param string $name
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    public function saveContext(string $name, ContextInterface $context): ContextInterface
    {
        $contextFilePath = $this->getContextFilePath($name);

        file_put_contents($contextFilePath, $this->contextSerializer->serialize($context));

        return $context;
    }

    /**
     * @param string $name
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\MissingContextFileException
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
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
     * @param string $name
     *
     * @return void
     */
    public function deleteByName(string $name): void
    {
        $contextFilePath = $this->getContextFilePath($name);

        if (is_file($contextFilePath)) {
            unlink($contextFilePath);
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getContextFilePath(string $name): string
    {
        $filePath = $name;

        if (preg_match('/\.context\.json$/', $filePath) != 1) {
            $filePath .= '.context.json';
        }

        if (preg_match('/^\//', $filePath) != 1) {
            $filePath = getcwd() . DIRECTORY_SEPARATOR . $filePath;
        }

        return $filePath;
    }
}
