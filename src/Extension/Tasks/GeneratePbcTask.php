<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks;

use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Extension\Tasks\Commands\ChangeNamesCommand;
use SprykerSdk\Sdk\Extension\Tasks\Commands\CheckGitCommand;
use SprykerSdk\Sdk\Extension\Tasks\Commands\GeneratePbcCommand;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use Symfony\Component\Console\Helper\ProcessHelper;

class GeneratePbcTask implements TaskInterface
{
    protected ProcessHelper $processHelper;

    /**
     * @param \Symfony\Component\Console\Helper\ProcessHelper $processHelper
     */
    public function __construct(ProcessHelper $processHelper)
    {
        $this->processHelper = $processHelper;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return 'Generate a new PBC project';
    }

    /**
     * @return array
     */
    public function getPlaceholders(): array
    {
        return [
            new Placeholder(
                '%sdk_dir%',
                'SDK_DIR',
                [],
                true,
            ),
            new Placeholder(
                '%boilerplate_url%',
                'PBC_TYPE',
            ),
            new Placeholder(
                '%pbc_name%',
                'STATIC',
                [
                    'name' => 'pbc_name',
                    'description' => 'Input name for new PBC',
                    'type' => 'string',
                ],
            ),
            new Placeholder(
                '%project_url%',
                'STATIC',
                [
                    'name' => 'project_url',
                    'description' => 'Input repository for new PBC',
                    'type' => 'string',
                ],
            ),
        ];
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'generate:php:pbc';
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return [
            new CheckGitCommand(),
            new GeneratePbcCommand(),
            new ChangeNamesCommand(),
        ];
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return '0.1.0';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }

    /**
     * @return string|null
     */
    public function getSuccessor(): ?string
    {
        return null;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface
     */
    public function getLifecycle(): LifecycleInterface
    {
        return new Lifecycle(
            new InitializedEventData(),
            new UpdatedEventData(),
            new RemovedEventData(),
        );
    }
}
