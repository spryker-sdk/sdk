<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Task\Command;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use VcsConnector\Exception\AdapterDoesNotExistException;
use VcsConnector\Resolver\VcsConfigurationResolverInterface;

class VcsCloneCommand implements ExecutableCommandInterface
{
    /**
     * @var \VcsConnector\Resolver\VcsConfigurationResolverInterface
     */
    protected VcsConfigurationResolverInterface $vcsConfigurationResolver;

    /**
     * @var string
     */
    protected string $sdkPath;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @param \VcsConnector\Resolver\VcsConfigurationResolverInterface $vcsConfigurationResolver
     * @param string $sdkPath
     * @param \SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem $filesystem
     */
    public function __construct(
        VcsConfigurationResolverInterface $vcsConfigurationResolver,
        string $sdkPath,
        Filesystem $filesystem
    ) {
        $this->vcsConfigurationResolver = $vcsConfigurationResolver;
        $this->sdkPath = $sdkPath;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getCommand(): string
    {
        return static::class;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        $resolvedValues = $context->getResolvedValues();
        $projectDirectory = $this->sdkPath . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'tmp';

        $this->filesystem->mkdir($projectDirectory);

        try {
            $this->vcsConfigurationResolver
                ->resolve((string)$resolvedValues['%vcs%'])
                ->clone(
                    $projectDirectory,
                    (string)$resolvedValues['%vcs-repository%'],
                );
        } catch (AdapterDoesNotExistException $exception) {
            $context->addMessage(static::class, new Message($exception->getMessage(), Message::ERROR));
            $context->setExitCode(1);

            return $context;
        }

        $context->setExitCode(0);

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
