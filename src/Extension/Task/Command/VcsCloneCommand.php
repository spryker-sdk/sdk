<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Task\Command;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use VcsConnector\Vcs\VcsConfigurationResolverInterface;

class VcsCloneCommand implements ExecutableCommandInterface
{
    /**
     * @var \VcsConnector\Vcs\VcsConfigurationResolverInterface
     */
    protected VcsConfigurationResolverInterface $vcsConfigurationResolver;

    /**
     * @var string
     */
    protected string $sdkPath;

    /**
     * @param \VcsConnector\Vcs\VcsConfigurationResolverInterface $vcsConfigurationResolver
     * @param string $sdkPath
     */
    public function __construct(VcsConfigurationResolverInterface $vcsConfigurationResolver, string $sdkPath)
    {
        $this->vcsConfigurationResolver = $vcsConfigurationResolver;
        $this->sdkPath = $sdkPath;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getCommand(): string
    {
        return '';
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        $resolvedValues = $context->getResolvedValues();

        $this->vcsConfigurationResolver
            ->resolve((string)$resolvedValues['%vcs%'])
            ->clone(
                $this->sdkPath . DIRECTORY_SEPARATOR . 'var',
                (string)$resolvedValues['%vcs_repository%'],
            );

        return $context;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return 'php';
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function getConverter(): ?ConverterInterface
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getStage(): string
    {
        return ContextInterface::DEFAULT_STAGE;
    }
}
