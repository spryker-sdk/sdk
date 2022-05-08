<?php

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class NextPbcStepsCommand implements ExecutableCommandInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        $resolvedValues = $context->getResolvedValues();
        $context->addMessage(static::class, new Message(
            sprintf('1. go to %s', getcwd() . DIRECTORY_SEPARATOR . $resolvedValues['%pbc_name%']),
        MessageInterface::SUCCESS
        ));
        $context->addMessage(static::class, new Message('2. Run composer update -W', MessageInterface::SUCCESS));
        $context->addMessage(static::class, new Message('3. Run docker/sdk up', MessageInterface::SUCCESS));

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
        return false;
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
}
