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
use SprykerSdk\Sdk\Extension\ValueResolvers\PbcPhpVersionValueResolver;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class GeneratePbcTask implements TaskInterface
{
    /**
     * @var \SprykerSdk\SdkContracts\Entity\CommandInterface[]
     */
    protected array $commands = [];

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     */
    public function __construct(
        array $commands
    ) {
        $this->commands = $commands;
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
                    'description' => 'Input repository for new PBC (e.g.: https://github.com/<user>/<project>.git)',
                    'type' => 'string',
                ],
            ),
            new Placeholder(
                '%' . PbcPhpVersionValueResolver::VALUE_NAME . '%',
                PbcPhpVersionValueResolver::VALUE_RESOLVER_NAME,
                [],
                true
            )
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
        return $this->commands;
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
     * @return bool
     */
    public function isOptional(): bool
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
