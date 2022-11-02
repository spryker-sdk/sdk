<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter\CommandXmlFormatterInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Enum\Setting;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ConfigManager implements ConfigManagerInterface
{
   /**
    * @var string
    */
    protected const IDEA_CONFIG_FOLDER_PATH = DIRECTORY_SEPARATOR . '.idea' . DIRECTORY_SEPARATOR . 'commandlinetools' . DIRECTORY_SEPARATOR;

    /**
     * @var string
     */
    protected const IDEA_CONFIG_FILE_NAME = 'Custom_Spryker_Sdk.xml';

    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter\CommandXmlFormatterInterface
     */
    protected CommandXmlFormatterInterface $commandXmlFormatter;

    /**
     * @var \Symfony\Component\Serializer\Encoder\XmlEncoder
     */
    protected XmlEncoder $xmlEncoder;

    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\CommandLoaderInterface
     */
    protected CommandLoaderInterface $commandLoader;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var string
     */
    protected string $executableFilePath;

    /**
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\CommandLoaderInterface $commandLoader
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter\CommandXmlFormatterInterface $commandXmlFormatter
     * @param \Symfony\Component\Serializer\Encoder\XmlEncoder $xmlEncoder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param string $executableFilePath
     */
    public function __construct(
        CommandLoaderInterface $commandLoader,
        CommandXmlFormatterInterface $commandXmlFormatter,
        XmlEncoder $xmlEncoder,
        SettingRepositoryInterface $settingRepository,
        string $executableFilePath
    ) {
        $this->commandXmlFormatter = $commandXmlFormatter;
        $this->xmlEncoder = $xmlEncoder;
        $this->commandLoader = $commandLoader;
        $this->settingRepository = $settingRepository;
        $this->executableFilePath = $executableFilePath;
    }

    /**
     * @return bool
     */
    public function createXmlFile(): bool
    {
        $ideCommands = $this->commandLoader->load();
        $executableFile = $this->executableFilePath;

        $arrayConfig = $this->prepareConfig($ideCommands, $executableFile);
        $xmlConfig = $this->xmlEncoder->encode($arrayConfig, XmlEncoder::FORMAT);

        $projectDirSetting = (string)$this->getSetting(Setting::PATH_PROJECT_DIR)->getValues();
        $ideaFolderPath = $projectDirSetting . static::IDEA_CONFIG_FOLDER_PATH;

        if (!is_dir($ideaFolderPath)) {
            mkdir($ideaFolderPath, 0777, true);
        }

        return !(file_put_contents($ideaFolderPath . static::IDEA_CONFIG_FILE_NAME, $xmlConfig) !== false);
    }

    /**
     * @param array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface> $ideCommands
     * @param string $sdkDir
     *
     * @return array
     */
    protected function prepareConfig(array $ideCommands, string $sdkDir): array
    {
        return [
            '@xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            '@xsi:noNamespaceSchemaLocation' => 'schemas/frameworkDescriptionVersion1.1.3.xsd',
            '@frameworkId' => 'spryker-sdk',
            '@name' => 'Spryker Sdk',
            '@invoke' => $sdkDir,
            '@alias' => 'spryker-sdk',
            '@enabled' => 'true',
            '@version' => 2,
            'command' => $this->formatCommands($ideCommands),
        ];
    }

    /**
     * @param string $settingName
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function getSetting(string $settingName): SettingInterface
    {
        $setting = $this->settingRepository->findOneByPath($settingName);

        if (!$setting) {
            throw new MissingSettingException(sprintf('Setting "%s" is missing', $settingName));
        }

        return $setting;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface> $ideCommands
     *
     * @return array
     */
    protected function formatCommands(array $ideCommands): array
    {
        $xmlCommands = [];

        foreach ($ideCommands as $ideCommand) {
            $xmlCommands[] = $this->commandXmlFormatter->format($ideCommand);
        }

        return $xmlCommands;
    }
}
