<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

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
     * {@inheritDoc}
     *
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $valueReceiver
     * @param string $unameInfo
     */
    public function __construct(InteractionProcessorInterface $valueReceiver, string $unameInfo)
    {
        parent::__construct($valueReceiver);
        $this->unameInfo = $unameInfo;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'PC_SYSTEM';
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Check system.';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return ValueTypeEnum::TYPE_STRING;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return static::ALIAS;
    }
}
