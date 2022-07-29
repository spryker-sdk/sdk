<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Task\Command;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\Sdk\Extension\Service\AppFileModifierInterface;
use SprykerSdk\Sdk\Extension\ValueResolver\AppPhpVersionValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class ChangePhpVersionCommand implements ExecutableCommandInterface
{
    /**
     * @var string
     */
    protected const COMPOSER_CHANGE_ERROR = 'Can not change PHP version in composer.json in generated App';

    /**
     * @var string
     */
    protected const DOCKER_INITIALIZATION_ERROR = 'Can not change PHP version deploy.dev.yml in generated App';

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
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        try {
            $this->changeComposerPhpVersion($context);
            $this->changeDockerPhpVersion($context);
        } catch (FileNotFoundException $exception) {
            $context->addMessage(static::class, new Message($exception->getMessage(), MessageInterface::ERROR));
        }

        return $context;
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

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function getConverter(): ?ConverterInterface
    {
        return null;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function changeComposerPhpVersion(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();

        $composerContent = $this->composerFileModifier->read($context, static::COMPOSER_CHANGE_ERROR);

        $phpVersion = $this->getPhpVersion($resolvedValues);

        if (isset($composerContent['require']['php'])) {
            $composerContent['require']['php'] = '>=' . $phpVersion;
        }

        if (isset($composerContent['config']['platform']['php'])) {
            $composerContent['config']['platform']['php'] = AppPhpVersionValueResolver::PHP_VERSIONS[$phpVersion];
        }

        $this->composerFileModifier->write($composerContent, $context);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function changeDockerPhpVersion(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();
        $dockerFileContent = $this->dockerFileModifier->read($context, static::DOCKER_INITIALIZATION_ERROR);
        $dockerFileContent['image']['tag'] = 'spryker/php:' . $this->getPhpVersion($resolvedValues);
        $this->dockerFileModifier->write($dockerFileContent, $context);
    }

    /**
     * @param array<string, mixed> $resolvedValues
     *
     * @return string
     */
    protected function getPhpVersion(array $resolvedValues): string
    {
        return $resolvedValues['%' . AppPhpVersionValueResolver::VALUE_NAME . '%'];
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return ContextInterface::DEFAULT_STAGE;
    }
}
