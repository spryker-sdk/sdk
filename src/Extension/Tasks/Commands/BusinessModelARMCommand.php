<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Extension\ValueResolvers\PCSystemValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use Symfony\Component\Yaml\Yaml;

class BusinessModelARMCommand implements ExecutableCommandInterface, ErrorCommandInterface
{
    /**
     * @var array<string>
     */
    protected const DEPLOY_FILES = [
        'deploy.dev.yml',
    ];

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yaml;

    /**
     * @param \Symfony\Component\Yaml\Yaml $yaml
     */
    public function __construct(Yaml $yaml)
    {
        $this->yaml = $yaml;
    }

    /**
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
        $system = $resolvedValues['%' . PCSystemValueResolver::ALIAS . '%'] ?? null;
        if ($system === PCSystemValueResolver::MAC_ARM) {
            $this->editDeployConfiguration($context);
            $this->editConfiguration($context);
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function editConfiguration(ContextInterface $context): void
    {
        $resolvedValues = $context->getResolvedValues();
        $system = $resolvedValues['%' . PCSystemValueResolver::ALIAS . '%'] ?? null;
        if ($system === PCSystemValueResolver::MAC_ARM) {
            return;
        }

        $filePath = $this->getAbsolutePathToFile('config/Shared/config_default.php');
        $content = file_get_contents($filePath);
        if ($content) {
            $position = strpos($content, 'SchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL');
            if ($position !== false) {
                $content = substr_replace(
                    $content,
                    'SchedulerJenkinsConfig::SCHEDULER_JENKINS_CSRF_ENABLED => (bool)getenv(\'SPRYKER_JENKINS_CSRF_PROTECTION_ENABLED\'),
            ',
                    $position,
                    0,
                );

                file_put_contents($filePath, $content);
            }
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function editDeployConfiguration(ContextInterface $context): void
    {
        foreach (static::DEPLOY_FILES as $deployFile) {
            $filePath = $this->getAbsolutePathToFile($deployFile);

            $content = $this->yaml::parseFile($filePath);
            $content['services']['broker']['version'] = '3.9';
            $content['services']['scheduler']['version'] = '2.324';
            $content['services']['scheduler']['csrf-protection-enabled'] = true;

            file_put_contents($filePath, $this->yaml::dump($content, 10));
        }
    }

    /**
     * @param string $relativeFilePath
     *
     * @return string
     */
    protected function getAbsolutePathToFile(string $relativeFilePath): string
    {
        return rtrim((string)getcwd()) . DIRECTORY_SEPARATOR . $relativeFilePath;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return 'Can\'t read files in project';
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
     * @return string
     */
    public function getStage(): string
    {
        return ContextInterface::DEFAULT_STAGE;
    }
}
