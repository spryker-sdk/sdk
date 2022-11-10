<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Infrastructure\Entity\Command;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class CommandMapper implements CommandMapperInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ConverterMapperInterface
     */
    protected ConverterMapperInterface $converterMapper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\CommandSplitterMapper
     */
    protected CommandSplitterMapper $commandSplitterMapper;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\ConverterMapperInterface $converterMapper
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\CommandSplitterMapper $commandSplitterMapper
     */
    public function __construct(ConverterMapperInterface $converterMapper, CommandSplitterMapper $commandSplitterMapper)
    {
        $this->converterMapper = $converterMapper;
        $this->commandSplitterMapper = $commandSplitterMapper;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Command
     */
    public function mapCommand(CommandInterface $command): Command
    {
        $commandStr = !($command instanceof ExecutableCommandInterface) || class_exists($command->getCommand())
            ? $command->getCommand()
            : get_class($command);

        $converter = $command->getConverter()
            ? $this->converterMapper->mapConverter($command->getConverter())
            : null;

        $errorMessage = $command instanceof ErrorCommandInterface
            ? $command->getErrorMessage()
            : '';

        // just a stub for POC. Method must be implemented in the SdkContracts
        $commandSplitter = method_exists($command, 'getCommandSplitter') && $command->getCommandSplitter()
            ? $this->commandSplitterMapper->mapDomainEntityToInfrastructure($command->getCommandSplitter())
            : null;

        return new Command(
            $commandStr,
            $command->getType(),
            $command->hasStopOnError(),
            $command->getTags(),
            $converter,
            $command->getStage(),
            $errorMessage,
            $commandSplitter,
        );
    }
}
