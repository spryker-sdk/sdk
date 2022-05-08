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
        $message = <<<MESSAGE

1. go to %s
2. Run "docker/sdk boot -s deploy.dev.yml" to spin up the local environment
3. Run "docker/sdk up" to run the PBC locally
4. Run "docker/sdk cli composer update -W" to ensure all dependencies are up to date
MESSAGE;

        $resolvedValues = $context->getResolvedValues();
        $context->addMessage(static::class, new Message(sprintf($message, $resolvedValues['%pbc_name%']), MessageInterface::SUCCESS));

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
