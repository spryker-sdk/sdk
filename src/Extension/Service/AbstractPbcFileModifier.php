<?php

namespace SprykerSdk\Sdk\Extension\Service;

use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

abstract class AbstractPbcFileModifier implements PbcFileModifierInterface
{
    /**
     * @return string
     */
    protected abstract function getFileName(): string;

    /**
     * @param string $content
     *
     * @return array
     */
    protected abstract function parseContent(string $content): array;

    /**
     * @param array $content
     *
     * @return string
     */
    protected abstract function dumpContent(array $content): string;

    /**
     * @param array $content
     * @param ContextInterface $context
     *
     * @return void
     */
    public function write(array $content, ContextInterface $context)
    {
        $filePath = $this->buildFilePath($context);
        file_put_contents($filePath, $this->dumpContent($content));
    }

    /**
     * @param ContextInterface $context
     * @param string|null $errorMessage
     *
     * @return array
     */
    public function read(ContextInterface $context, ?string $errorMessage = null)
    {
        $filePath = $this->buildFilePath($context);

        if (!file_exists($filePath)) {
            throw new FileNotFoundException($this->buildErrorMessage($errorMessage, $filePath));
        }

        return $this->parseContent(file_get_contents($filePath));
    }

    /**
     * @param callable $replacementFunction
     * @param ContextInterface $context
     * @param string|null $errorMessage
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
     * @param ContextInterface $context
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
