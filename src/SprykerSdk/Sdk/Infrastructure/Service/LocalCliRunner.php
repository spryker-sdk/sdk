<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

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
            return match(gettype($value)) {
                default => (string) $value,
            };
        }, array_values($resolvedValues));

        $assembledCommand = preg_replace($placeholders, $values, $command->getCommand());

        $process = Process::fromShellCommandline($assembledCommand);
        $process->setTimeout(null);
        $process->setIdleTimeout(null);

        $process = $this->processHelper->run(
            $this->output,
            [$process]
        );

        return $process->run();
    }
}
