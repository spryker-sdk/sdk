<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Task\Command;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\Sdk\Extension\Service\AppFileModifierInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class ChangeNamesCommand implements ExecutableCommandInterface
{
    /**
     * @var string
     */
    protected const COMPOSER_INITIALIZATION_ERROR = 'Can not change name composer.json in generated App';

    /**
     * @var string
     */
    protected const DOCKER_INITIALIZATION_ERROR = 'Can not change name in deploy.dev.yml in generated App';

    /**
     * @var \SprykerSdk\Sdk\Extension\Service\AppFileModifierInterface
     */
    protected AppFileModifierInterface $composerFileModifier;

    /**
     * @var \SprykerSdk\Sdk\Extension\Service\AppFileModifierInterface
     */
    protected AppFileModifierInterface $dockerFileModifier;

    /**
     * @param \SprykerSdk\Sdk\Extension\Service\AppFileModifierInterface $composerFileModifier
     * @param \SprykerSdk\Sdk\Extension\Service\AppFileModifierInterface $dockerFileModifier
     */
    public function __construct(
        AppFileModifierInterface $composerFileModifier,
        AppFileModifierInterface $dockerFileModifier
    ) {
        $this->composerFileModifier = $composerFileModifier;
        $this->dockerFileModifier = $dockerFileModifier;
    }

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        try {
            $this->changeComposerNames($context);
            $this->changeDockerNames($context);
        } catch (FileNotFoundException $exception) {
            $context->addMessage(static::class, new Message($exception->getMessage(), MessageInterface::ERROR));
        }

        return $context;
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

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function changeComposerNames(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();
        $newRepositoryName = strtolower(
            basename(dirname($resolvedValues['%project_url%']))
            . '/'
            . basename($resolvedValues['%project_url%'], '.git'),
        );
        $composerContent = $this->composerFileModifier->read($context, static::COMPOSER_INITIALIZATION_ERROR);
        $composerContent['name'] = $newRepositoryName;
        $this->composerFileModifier->write($composerContent, $context);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function changeDockerNames(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();
        $appName = strtolower($resolvedValues['%app_name%']);
        $appName = preg_replace('/[\s]/', '-', $appName);

        $dockerFileContent = $this->dockerFileModifier->read($context, static::DOCKER_INITIALIZATION_ERROR);
        $dockerFileContent['namespace'] = $appName;
        $this->dockerFileModifier->write($dockerFileContent, $context);
        $this->dockerFileModifier->replace(function (string $content) use ($appName) {
            return str_replace('spryker.local', $appName . '.local', $content);
        }, $context, static::DOCKER_INITIALIZATION_ERROR);
    }
}
