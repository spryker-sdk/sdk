<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class ChangeNamesCommand implements ExecutableCommandInterface
{
    /**
     * @var string
     */
    protected const COMPOSER_INITIALIZATION_ERROR = 'Can not initialize composer.json in generated PBC';

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        $resolvedValues = $context->getResolvedValues();

        $repositoryName = basename($resolvedValues['%boilerplate_url%']);
        $newRepositoryName = basename($resolvedValues['%project_url%']);
        $composerFilePath = $resolvedValues['%pbc_name%'] . DIRECTORY_SEPARATOR . 'composer.json';

        if (!file_exists($composerFilePath)) {
            $context->addMessage(static::class, new Message(static::COMPOSER_INITIALIZATION_ERROR, MessageInterface::ERROR));

            return $context;
        }

        $text = file_get_contents($composerFilePath);
        $text = str_replace($repositoryName, $newRepositoryName, (string)$text);
        file_put_contents($composerFilePath, $text);

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
