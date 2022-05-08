<?php

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\Sdk\Extension\ValueResolvers\PbcPhpVersionValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Yaml\Yaml;

class ChangePhpVersionCommand implements ExecutableCommandInterface
{
    /**
     * @var string
     */
    protected const COMPOSER_CHANGE_ERROR = 'Can not change PHP version in composer.json in generated PBC';

    /**
     * @var string
     */
    protected const DOCKER_INITIALIZATION_ERROR = 'Can not change PHP version deploy.dev.yml in generated PBC';

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
    public function getViolationConverter(): ?ConverterInterface
    {
        return null;
    }

    /**
     * @param ContextInterface $context
     *
     * @return void
     */
    protected function changeComposerPhpVersion(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();
        $composerFilePath = $this->getPbcName($resolvedValues) . DIRECTORY_SEPARATOR . 'composer.json';

        if (!file_exists($composerFilePath)) {
            throw new FileNotFoundException(static::COMPOSER_CHANGE_ERROR);
        }

        $composerContent = json_decode(file_get_contents($composerFilePath), true);

        $phpVersion = $this->getPhpVersion($resolvedValues[PbcPhpVersionValueResolver::VALUE_NAME]);

        if (isset($composerContent['require']['php'])) {
            $composerContent['require']['php'] = '>=' . $phpVersion;
        }

        if (isset($composerContent['config']['platform']['php'])) {
            $composerContent['config']['platform']['php'] = PbcPhpVersionValueResolver::PHP_VERSIONS[$phpVersion];
        }

        file_put_contents($composerFilePath, json_encode($composerContent));
    }

    /**
     * @param ContextInterface $context
     *
     * @return void
     */
    protected function changeDockerPhpVersion(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();
        $pbcName = $this->getPbcName($resolvedValues);
        $dockerDeploymentFilePath = $pbcName . DIRECTORY_SEPARATOR . 'deploy.dev.yml';

        if (!file_exists($dockerDeploymentFilePath)) {
            throw new FileNotFoundException(static::DOCKER_INITIALIZATION_ERROR);
        }

        $dockerFileContent = $this->yamlParser->parseFile($dockerDeploymentFilePath);
        $dockerFileContent['image']['tag'] = 'spryker/php:' . $this->getPhpVersion($resolvedValues);
        $text = $this->yamlParser->dump($dockerFileContent);

        file_put_contents($dockerFileContent, $text);
    }

    /**
     * @param array<string, mixed> $resolvedValues
     *
     * @return string
     */
    protected function getPhpVersion(array $resolvedValues): string
    {
        return $resolvedValues['%' . PbcPhpVersionValueResolver::VALUE_NAME . '%'];
    }

    /**
     * @param array<string, mixed> $resolveValues
     *
     * @return string
     */
    protected function getPbcName(array $resolveValues): string
    {
        return $resolveValues['%pbc_name%'];
    }
}
