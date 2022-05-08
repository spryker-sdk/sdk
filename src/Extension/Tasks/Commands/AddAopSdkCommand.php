<?php

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class AddAopSdkCommand implements ExecutableCommandInterface
{
    protected const AOP_SDK_REPOSITORY = 'spryker-sdk/aop-sdk';

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        try {
            $this->addAopSdk($context);
        } catch (FileNotFoundException $exception) {
            $context->addMessage(static::class, new Message($exception->getMessage(), MessageInterface::ERROR));
        }

        return $context;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'php';
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function getViolationConverter(): ?ConverterInterface
    {
        return null;
    }

    /**
     * @param ContextInterface $context
     *
     * @return void
     */
    protected function addAopSdk(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();
        $composerFilePath = $this->getPbcName($resolvedValues) . DIRECTORY_SEPARATOR . 'composer.json';

        if (!file_exists($composerFilePath)) {
            throw new FileNotFoundException(sprintf('Can not add %s to composer.json in generated PBC', static::AOP_SDK_REPOSITORY));
        }

        $composerContent = json_decode(file_get_contents($composerFilePath), true);
        $composerContent['require'][static::AOP_SDK_REPOSITORY] = '*';

        file_put_contents($composerFilePath, json_encode($composerContent));
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
}
