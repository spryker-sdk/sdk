<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\ValueObject;

use InvalidArgumentException;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class ConfigurableCommand implements CommandInterface, ExecutableCommandInterface
{
    /**
     * @var \SprykerSdk\SdkContracts\Entity\CommandInterface&\SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface
     */
    protected CommandInterface $command;

    /**
     * @var bool|null
     */
    protected ?bool $stopOnError;

    /**
     * @var array|null
     */
    protected ?array $tags;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param bool|null $stopOnError
     * @param array<string>|null $tags
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(CommandInterface $command, ?bool $stopOnError = null, ?array $tags = null)
    {
        if (!($command instanceof ExecutableCommandInterface)) {
            throw new InvalidArgumentException(sprintf('Command %s should be executable', get_class($command)));
        }

        $this->command = $command;
        $this->stopOnError = $stopOnError;
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command->getCommand();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->command->getType();
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->tags ?? $this->command->getTags();
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return $this->stopOnError ?? $this->command->hasStopOnError();
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function getConverter(): ?ConverterInterface
    {
        return $this->command->getConverter();
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return $this->command->getStage();
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        return $this->command->execute($context);
    }
}
