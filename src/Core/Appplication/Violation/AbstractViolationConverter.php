<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Violation;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\SdkContracts\Violation\ViolationConverterInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

abstract class AbstractViolationConverter implements ViolationConverterInterface
{
    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @var string
     */
    protected string $producer;

    /**
     * @var array
     */
    public const LAYERS = ['Client', 'Yves', 'Shared', 'Service', 'Zed', 'Glue'];

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @return string|null
     */
    protected function readFile(): ?string
    {
        $reportDirectory = $this->settingRepository->findOneByPath('report_dir');

        if (!$reportDirectory) {
            return null;
        }

        $reportDirectory = $reportDirectory->getValues() . DIRECTORY_SEPARATOR . $this->fileName;

        $jsonReport = file_get_contents($reportDirectory);

        return $jsonReport ?: null;
    }

    /**
     * @param string $relatedPathToFile
     *
     * @return array
     */
    protected function resolveEntityNamesByPath(string $relatedPathToFile): array
    {
        $layers = implode('|', static::LAYERS);
        preg_match(sprintf('~(%s)/(\w+)/~', $layers), $relatedPathToFile, $matches);
        $moduleName = $matches[2];
        preg_match(sprintf('~(\w+/)+(%s)/(\w+)~', $layers), $relatedPathToFile, $matches);
        $pathToModule = $matches[0];
        preg_match(sprintf('~/(%s)/([a-zA-Z/]+)~', $layers), $relatedPathToFile, $matches);
        $classNamespace = str_replace(DIRECTORY_SEPARATOR, '\\', $matches[0]);

        return [$moduleName, $pathToModule, $classNamespace];
    }

    /**
     * @param array $configuration
     *
     * @return void
     */
    abstract public function configure(array $configuration): void;

    /**
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    abstract public function convert(): ?ViolationReportInterface;
}
