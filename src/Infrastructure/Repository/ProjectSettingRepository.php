<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as InfrastructureSetting;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use SprykerSdk\Sdk\Infrastructure\Resolver\PathResolver;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class ProjectSettingRepository implements ProjectSettingRepositoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $coreSettingRepository;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var string
     */
    protected string $projectSettingsFileName;

    /**
     * @var string
     */
    protected string $projectLocalSettingsFileName;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Resolver\PathResolver
     */
    protected PathResolver $pathResolver;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $coreSettingRepository
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $projectSettingsFileName
     * @param string $projectLocalSettingsFileName
     * @param \SprykerSdk\Sdk\Infrastructure\Resolver\PathResolver $pathResolver
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     */
    public function __construct(
        SettingRepositoryInterface $coreSettingRepository,
        Yaml $yamlParser,
        string $projectSettingsFileName,
        string $projectLocalSettingsFileName,
        PathResolver $pathResolver,
        Filesystem $filesystem
    ) {
        $this->coreSettingRepository = $coreSettingRepository;
        $this->yamlParser = $yamlParser;
        $this->projectSettingsFileName = $projectSettingsFileName;
        $this->projectLocalSettingsFileName = $projectLocalSettingsFileName;
        $this->pathResolver = $pathResolver;
        $this->filesystem = $filesystem;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface
    {
        return $this->saveMultiple([$setting])[0];
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function saveMultiple(array $settings): array
    {
        $localProjectValues = $this->fetchProjectValues($this->projectLocalSettingsFileName);
        $sharedProjectValues = $this->fetchProjectValues($this->projectSettingsFileName);

        /** @var \SprykerSdk\Sdk\Core\Domain\Entity\Setting $setting */
        foreach ($settings as $setting) {
            if ($setting->isShared()) {
                $sharedProjectValues[$setting->getPath()] = $setting->getValues();

                continue;
            }
            $localProjectValues[$setting->getPath()] = $setting->getValues();
        }

        $projectSettingDir = dirname($this->projectSettingsFileName);

        if (!is_dir($projectSettingDir)) {
            mkdir($projectSettingDir, 0777, true);
        }

        if ($localProjectValues) {
            $this->filesystem->dumpFile($this->projectLocalSettingsFileName, $this->yamlParser::dump($localProjectValues));
        }

        if ($sharedProjectValues) {
            $this->filesystem->dumpFile($this->projectSettingsFileName, $this->yamlParser::dump($sharedProjectValues));
        }

        return $settings;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $settingPath
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface
    {
        $coreSetting = $this->coreSettingRepository->findOneByPath($settingPath);

        if (!$coreSetting) {
            return $coreSetting;
        }

        $coreSetting = $this->resolvePathSetting($coreSetting);

        return $this->fillProjectValues([$coreSetting])[0];
    }

    /**
     * {@inheritDoc}
     *
     * @param string $settingPath
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function getOneByPath(string $settingPath): SettingInterface
    {
        $setting = $this->findOneByPath($settingPath);

        if (!$setting) {
            throw new MissingSettingException(sprintf('Setting by path "%s" not found. You need to run `sdk:init:project` command', $settingPath));
        }

        return $setting;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function find(): array
    {
        $entities = $this->coreSettingRepository->findProjectSettings();
        foreach ($entities as $key => $entity) {
            $entities[$key] = $this->resolvePathSetting($entity);
        }

        if (!$entities) {
            return [];
        }

        return $this->fillProjectValues($entities);
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function findProjectSettings(): array
    {
        return $this->find();
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $entities
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    protected function fillProjectValues(array $entities): array
    {
        $projectValues = $this->getProjectValues();

        /** @var \SprykerSdk\Sdk\Core\Domain\Entity\Setting $entity */
        foreach ($entities as $entity) {
            if ($entity->isSdk()) {
                continue;
            }

            if (array_key_exists($entity->getPath(), $projectValues)) {
                $entity->setValues($projectValues[$entity->getPath()]);
            }
        }

        return $entities;
    }

    /**
     * @return array
     */
    protected function getProjectValues(): array
    {
        return array_merge(
            $this->fetchProjectValues($this->projectSettingsFileName),
            $this->fetchProjectValues($this->projectLocalSettingsFileName),
        );
    }

    /**
     * @param string $settingPath
     *
     * @return array
     */
    protected function fetchProjectValues(string $settingPath): array
    {
        if (!$this->isReadableFile($settingPath)) {
            return [];
        }

        return (array)$this->yamlParser::parseFile($settingPath, $this->yamlParser::PARSE_CONSTANT);
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function findCoreSettings(): array
    {
        return $this->coreSettingRepository->findCoreSettings();
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function initSettingDefinition(): array
    {
        return $this->coreSettingRepository->initSettingDefinition();
    }

    /**
     * @param array<string> $paths
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function findByPaths(array $paths): array
    {
        return $this->fillProjectValues(
            $this->coreSettingRepository->findByPaths($paths),
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function resolvePathSetting(SettingInterface $setting)
    {
        if (!$setting instanceof InfrastructureSetting) {
            throw new InvalidTypeException('Setting need to be of type ' . InfrastructureSetting::class);
        }
        if ($setting->getType() === 'path') {
            $values = $setting->getValues();
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $values[$key] = $this->pathResolver->getResolveProjectRelativePath($value);
                }
            }
            if (is_string($values)) {
                $values = $this->pathResolver->getResolveProjectRelativePath($values);
            }

            $setting->setValues($values);
        }

        return $setting;
    }

    /**
     * @param string $localProjectSettingPath
     *
     * @return bool
     */
    protected function isReadableFile(string $localProjectSettingPath): bool
    {
        return is_file($localProjectSettingPath) && is_readable($localProjectSettingPath);
    }
}
