<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class ChangeNamesCommand implements ExecutableCommandInterface
{
    /**
     * @param array $resolvedValues
     *
     * @return \SprykerSdk\SdkContracts\CommandRunner\CommandResponseInterface
     */
    public function execute(array $resolvedValues): CommandResponseInterface
    {
        $repositoryName = basename($resolvedValues['%boilerplate_url%']);
        $newRepositoryName = basename($resolvedValues['%project_url%']);
        $composerFilePath = $resolvedValues['%pbc_name%'] . DIRECTORY_SEPARATOR . 'composer.json';

        $commandResponse = new CommandResponse(true);
        if (!file_exists($composerFilePath)) {
            return $commandResponse
                ->setIsSuccessful(false)
                ->setErrorMessage('Can not initialize composer.json in generated PBC');
        }

        $text = file_get_contents($composerFilePath);
        $text = str_replace($repositoryName, $newRepositoryName, (string)$text);
        file_put_contents($composerFilePath, $text);

        return $commandResponse;
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
