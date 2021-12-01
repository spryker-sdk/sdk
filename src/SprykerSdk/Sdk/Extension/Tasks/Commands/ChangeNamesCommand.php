<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Contracts\Entity\ExecutableCommandInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeNamesCommand implements ExecutableCommandInterface
{
    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $resolvedValues
     *
     * @return int
     */
    public function execute(OutputInterface $output, array $resolvedValues): int
    {
        $repositoryName = basename($resolvedValues['%boilerplate_url%']);
        $newRepositoryName = basename($resolvedValues['%project_url%']);
        $composerFilePath = $resolvedValues['%pbc_name%'] . DIRECTORY_SEPARATOR . 'composer.json';

        $text = file_get_contents($composerFilePath);

        $text = str_replace($repositoryName, $newRepositoryName, $text);

        file_put_contents($composerFilePath, $text);die;
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
}
