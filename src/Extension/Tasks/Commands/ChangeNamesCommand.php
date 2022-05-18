<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\Sdk\Extension\Service\PbcFileModifierInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class ChangeNamesCommand implements ExecutableCommandInterface
{
    /**
     * @var string
     */
    protected const COMPOSER_INITIALIZATION_ERROR = 'Can not change name composer.json in generated PBC';

    /**
     * @var string
     */
    protected const DOCKER_INITIALIZATION_ERROR = 'Can not change name in deploy.dev.yml in generated PBC';

    private PbcFileModifierInterface $composerFileModifier;

    private PbcFileModifierInterface $dockerFileModifier;

    /**
     * @param \SprykerSdk\Sdk\Extension\Service\PbcFileModifierInterface $composerFileModifier
     * @param \SprykerSdk\Sdk\Extension\Service\PbcFileModifierInterface $dockerFileModifier
     */
    public function __construct(
        PbcFileModifierInterface $composerFileModifier,
        PbcFileModifierInterface $dockerFileModifier
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
            $this->changeComposerNames($context);
            $this->changeDockerNames($context);
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
        return static::class;
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
    public function getViolationConverter(): ?ConverterInterface
    {
        return null;
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
        $pbcName = $resolvedValues['%pbc_name%'];

        $dockerFileContent = $this->dockerFileModifier->read($context, static::DOCKER_INITIALIZATION_ERROR);
        $dockerFileContent['namespace'] = $pbcName;
        $this->dockerFileModifier->write($dockerFileContent, $context);
        $this->dockerFileModifier->replace(function (string $content) use ($pbcName) {
            return str_replace('spryker.local', $pbcName . '.local', $content);
        }, $context, static::DOCKER_INITIALIZATION_ERROR);

    /**
     * @return string
     */
    public function getStage(): string
    {
        return ContextInterface::DEFAULT_STAGE;
    }
}
