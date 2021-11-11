<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitSettingsConsole extends AbstractSdkConsole
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'init';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Init required settings for SDK.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp($this->getHelpText());

        foreach ($this->getFacade()->getRequiredSettings() as $settingDefinition) {
            $this->addOption(
                $settingDefinition['name'],
                null,
                $settingDefinition['mode'],
                $settingDefinition['description']
            );
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->setSetting($input->getOptions(), $this->getFactory()->createStyle($input, $output));

        return static::SUCCESS;
    }

    /**
     * @return string
     */
    protected function getHelpText(): string
    {
        return 'Defined settings to SDK `<info>SDK</info>`.';
    }
}
