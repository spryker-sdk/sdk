<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\SdkTasksBundle\Service;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkTasksBundle\Exception\FileNotFoundException;

abstract class AbstractPbcFileModifier implements PbcFileModifierInterface
{
    /**
     * @return string
     */
    abstract protected function getFileName(): string;

    /**
     * @param string $content
     *
     * @return array
     */
    abstract protected function parseContent(string $content): array;

    /**
     * @param array $content
     *
     * @return string
     */
    abstract protected function dumpContent(array $content): string;

    /**
     * @param array $content
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function write(array $content, ContextInterface $context)
    {
        $filePath = $this->getFileName();
        file_put_contents($filePath, $this->dumpContent($content));
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param string|null $errorMessage
     *
     * @throws \SprykerSdk\SdkTasksBundle\Exception\FileNotFoundException
     *
     * @return array
     */
    public function read(ContextInterface $context, ?string $errorMessage = null)
    {
        $filePath = $this->getFileName();

        if (!file_exists($filePath)) {
            throw new FileNotFoundException($this->buildErrorMessage($errorMessage, $filePath));
        }

        $content = file_get_contents($filePath);

        if (!$content) {
            throw new FileNotFoundException($this->buildErrorMessage($errorMessage, $filePath));
        }

        return $this->parseContent($content);
    }

    /**
     * @param callable $replacementFunction
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param string|null $errorMessage
     *
     * @throws \SprykerSdk\SdkTasksBundle\Exception\FileNotFoundException
     *
     * @return void
     */
    public function replace(callable $replacementFunction, ContextInterface $context, ?string $errorMessage = null)
    {
        $filePath = $this->getFileName();

        if (!file_exists($filePath)) {
            throw new FileNotFoundException($this->buildErrorMessage($errorMessage, $filePath));
        }

        $content = file_get_contents($filePath);
        $content = call_user_func($replacementFunction, $content);

        file_put_contents($filePath, $content);
    }

    /**
     * @param string|null $errorMessage
     * @param string $filePath
     *
     * @return string
     */
    protected function buildErrorMessage(?string $errorMessage, string $filePath): string
    {
        return $errorMessage ?: $filePath . ' could not be read from';
    }
}
