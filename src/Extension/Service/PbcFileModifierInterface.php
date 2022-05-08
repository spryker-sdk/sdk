<?php

namespace SprykerSdk\Sdk\Extension\Service;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

interface PbcFileModifierInterface
{
    /**
     * @param array $content
     * @param ContextInterface $context
     *
     * @return void
     */
    public function write(array $content, ContextInterface $context);

    /**
     * @param ContextInterface $context
     * @param string|null $errorMessage
     *
     * @return array
     */
    public function read(ContextInterface $context, ?string $errorMessage = null);

    /**
     * @param callable $replacementFunction
     * @param ContextInterface $context
     * @param string|null $errorMessage
     *
     * @return void
     */
    public function replace(callable $replacementFunction, ContextInterface $context, ?string $errorMessage = null);
}
