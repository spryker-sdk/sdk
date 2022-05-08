<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Service;

use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

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
        $filePath = $this->buildFilePath($context);
        file_put_contents($filePath, $this->dumpContent($content));
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param string|null $errorMessage
     *
     * @throws \SprykerSdk\Sdk\Extension\Exception\FileNotFoundException
     *
     * @return array
     */
    public function read(ContextInterface $context, ?string $errorMessage = null)
    {
        $filePath = $this->buildFilePath($context);

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
     * @throws \SprykerSdk\Sdk\Extension\Exception\FileNotFoundException
     *
     * @return void
     */
    public function replace(callable $replacementFunction, ContextInterface $context, ?string $errorMessage = null)
    {
        $filePath = $this->buildFilePath($context);

        if (!file_exists($filePath)) {
            throw new FileNotFoundException($this->buildErrorMessage($errorMessage, $filePath));
        }

        $content = file_get_contents($filePath);
        $content = call_user_func($replacementFunction, $content);

        file_put_contents($filePath, $content);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return string
     */
    protected function buildFilePath(ContextInterface $context): string
    {
        return $this->getPbcName($context->getResolvedValues()) . DIRECTORY_SEPARATOR . $this->getFileName();
    }

    /**
     * @param array<string, mixed> $resolveValues
     *
     * @return string
     */
    protected function getPbcName(array $resolveValues): string
    {
        return $resolveValues['%pbc_name%'];
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
