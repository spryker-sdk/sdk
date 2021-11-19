<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandRunnerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\OutputInterface;

class LocalCliRunner implements CommandRunnerInterface
{
    protected OutputInterface $output;

    public function __construct(
        protected ProcessHelper $processHelper,
    ) {}

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function setHelperSet(HelperSet $helperSet)
    {
        $this->processHelper->setHelperSet($helperSet);
    }

    /**
     * @param CommandInterface $command
     *
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool
    {
        return $command->getType() === 'local_cli';
    }

    /**
     * @param CommandInterface $command
     * @param array $resolvedValues
     *
     * @return int
     */
    public function execute(CommandInterface $command, array $resolvedValues): int
    {
        $placeholders = array_map(function (mixed $placeholder): string {
            return '/' . preg_quote((string)$placeholder, '/') . '/';
        }, array_keys($resolvedValues));

        $values = array_map(function (mixed $value): string {
            $castedValue = match(gettype($value)) {
                //@todo format bool, int, float
                default => (string) $value,
            };

            return preg_quote($castedValue);
        }, array_values($resolvedValues));

        $assembledCommand = preg_replace($placeholders, $values, $command->getCommand());

        $process = $this->processHelper->run(
            $this->output,
            [$assembledCommand]
        );

        return $process->run();
    }
}