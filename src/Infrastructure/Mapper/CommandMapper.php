<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Infrastructure\Entity\Command;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class CommandMapper implements CommandMapperInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ConverterMapperInterface
     */
    protected ConverterMapperInterface $converterMapper;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\ConverterMapperInterface $converterMapper
     */
    public function __construct(ConverterMapperInterface $converterMapper)
    {
        $this->converterMapper = $converterMapper;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Command>
     */
    public function mapCommand(CommandInterface $command): Command
    {
        return new Command(
            $command instanceof ExecutableCommandInterface || $command->getType() === 'php' ?
                get_class($command) :
                $command->getCommand(),
            $command->getType(),
            $command->hasStopOnError(),
            $command->getTags(),
            $command->getViolationConverter() ? $this->converterMapper->mapConverter($command->getViolationConverter()) : null,
        );
    }
}
