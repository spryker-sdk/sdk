<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Yaml\Yaml;

class ChangeNamesCommand implements ExecutableCommandInterface
{
    /**
     * @var string
     */
    protected const COMPOSER_INITIALIZATION_ERROR = 'Can not initialize composer.json in generated PBC';

    /**
     * @var string
     */
    protected const DOCKER_INITIALIZATION_ERROR = 'Can not initialize deploy.dev.yml in generated PBC';

    protected Yaml $yamlParser;

    /**
     * @param Yaml $yamlParser
     */
    public function __construct(Yaml $yamlParser)
    {
        $this->yamlParser = $yamlParser;
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
    public function getViolationConverter(): ?ConverterInterface
    {
        return null;
    }

    /**
     * @param ContextInterface $context
     *
     * @return void
     */
    protected function changeComposerNames(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();
        $repositoryName = basename($resolvedValues['%boilerplate_url%']);
        $newRepositoryName = basename($resolvedValues['%project_url%']);
        $composerFilePath = $resolvedValues['%pbc_name%'] . DIRECTORY_SEPARATOR . 'composer.json';

        if (!file_exists($composerFilePath)) {
            throw new FileNotFoundException(static::COMPOSER_INITIALIZATION_ERROR);
        }

        $text = file_get_contents($composerFilePath);
        $text = str_replace($repositoryName, $newRepositoryName, (string)$text);
        file_put_contents($composerFilePath, $text);
    }

    /**
     * @param ContextInterface $context
     *
     * @return void
     */
    protected function changeDockerNames(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();
        $pbcName = $resolvedValues['%pbc_name%'];
        $dockerDeploymentFilePath = $pbcName . DIRECTORY_SEPARATOR . 'deploy.dev.yml';

        if (!file_exists($dockerDeploymentFilePath)) {
            throw new FileNotFoundException(static::DOCKER_INITIALIZATION_ERROR);
        }

        $dockerFileContent = $this->yamlParser->parseFile($dockerDeploymentFilePath);
        $dockerFileContent['namespace'] = $pbcName;
        $text = $this->yamlParser->dump($dockerFileContent);
        $text = str_replace('spryker.local', $pbcName . '.local', $text);

        file_put_contents($dockerFileContent, $text);
    }
}
