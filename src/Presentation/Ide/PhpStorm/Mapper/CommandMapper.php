<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper;

use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Command;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CommandMapper implements CommandMapperInterface
{
    protected OptionMapperInterface $optionMapper;

    protected ParamMapperInterface $paramMapper;

    /**
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\OptionMapperInterface $optionMapper
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\ParamMapperInterface $paramMapper
     */
    public function __construct(OptionMapperInterface $optionMapper, ParamMapperInterface $paramMapper)
    {
        $this->optionMapper = $optionMapper;
        $this->paramMapper = $paramMapper;
    }

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface
     */
    public function mapToIdeCommand(SymfonyCommand $command): CommandInterface
    {
        return new Command(
            (string)$command->getName(),
            $this->mapParams($command),
            $this->mapOptions($command),
            $command->getHelp(),
        );
    }

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\ParamInterface>
     */
    protected function mapParams(SymfonyCommand $command): array
    {
        $params = [];

        foreach ($command->getNativeDefinition()->getArguments() as $argument) {
            $params[] = $this->paramMapper->mapToIdeParam($argument);
        }

        return $params;
    }

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\OptionInterface>
     */
    protected function mapOptions(SymfonyCommand $command): array
    {
        $options = [];

        foreach ($command->getNativeDefinition()->getOptions() as $option) {
            $options[] = $this->optionMapper->mapToIdeOption($option);
        }

        return $options;
    }
}
