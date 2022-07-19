<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Appplication\ValueResolver\AbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class PCSystemValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    public const ID = 'PC_SYSTEM';

    /**
     * @var string
     */
    public const ALIAS = 'pc_system';

    /**
     * @var string
     */
    public const LINUX = 'linux';

    /**
     * @var string
     */
    public const MAC = 'mac';

    /**
     * @var string
     */
    public const MAC_ARM = 'mac_arm';

    /**
     * @var string
     */
    protected string $unameInfo;

    /**
     * @var array
     */
    protected const SYSTEMS_REGEX = [
        'linux' => '/(?<system>Linux)/',
        'mac_arm' => '/(?<system>(?<os>Darwin).*(?<arch>ARM64))/',
        'mac' => '/(?<system>Darwin)/',
    ];

    /**
     * @param \SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface $valueReceiver
     * @param string $unameInfo
     */
    public function __construct(ValueReceiverInterface $valueReceiver, string $unameInfo)
    {
        parent::__construct($valueReceiver);
        $this->unameInfo = $unameInfo;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'PC_SYSTEM';
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return string
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false): string
    {
        foreach (static::SYSTEMS_REGEX as $system => $regex) {
            preg_match($regex, $this->unameInfo, $matches);
            if (isset($matches['system'])) {
                return $system;
            }
        }

        return '';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Check system.';
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return static::ALIAS;
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return null;
    }

    /**
     * @param array<string, mixed> $settingValues
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues): mixed
    {
        return [];
    }

    /**
     * @param array $settingValues
     * @param array $resolvedValues
     *
     * @return array
     */
    public function getChoiceValues(array $settingValues, array $resolvedValues = []): array
    {
        return [];
    }
}
