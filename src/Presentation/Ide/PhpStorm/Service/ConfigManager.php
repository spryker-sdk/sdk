<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter\CommandXmlFormatterInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ConfigManager implements ConfigManagerInterface
{
    /**
     * @var string
     */
    protected const SDK_DIR_PATH = 'sdk_dir';

    /**
     * @var string
     */
    protected const PROJECT_DIR_PATH = 'project_dir';

    /**
     * @var string
     */
    protected const IDEA_CONFIG_FOLDER_PATH = DIRECTORY_SEPARATOR . '.idea' . DIRECTORY_SEPARATOR . 'commandlinetools' . DIRECTORY_SEPARATOR;

    /**
     * @var string
     */
    protected const IDEA_CONFIG_FILE_NAME = 'Custom_Spryker_Sdk.xml';

    protected CommandXmlFormatterInterface $commandXmlFormatter;

    protected XmlEncoder $xmlEncoder;

    protected CommandLoaderInterface $commandLoader;

    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\CommandLoaderInterface $commandLoader
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter\CommandXmlFormatterInterface $commandXmlFormatter
     * @param \Symfony\Component\Serializer\Encoder\XmlEncoder $xmlEncoder
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(
        CommandLoaderInterface $commandLoader,
        CommandXmlFormatterInterface $commandXmlFormatter,
        XmlEncoder $xmlEncoder,
        SettingRepositoryInterface $settingRepository
    ) {
        $this->commandXmlFormatter = $commandXmlFormatter;
        $this->xmlEncoder = $xmlEncoder;
        $this->commandLoader = $commandLoader;
        $this->settingRepository = $settingRepository;
    }

    /**
     * @return bool
     */
    public function createXmlFile(): bool
    {
        $ideCommands = $this->commandLoader->load();
        $executableFile = getenv('EXECUTABLE_FILE_PATH');
        if ($executableFile === false || !\is_string($executableFile)) {
            $executableFile = (string)$this->getSetting(static::SDK_DIR_PATH)->getValues();
            $executableFile = '"$PhpExecutable$" ' . $executableFile . '/bin/console';
        }

        $arrayConfig = $this->prepareConfig($ideCommands, $executableFile);
        $xmlConfig = $this->xmlEncoder->encode($arrayConfig, XmlEncoder::FORMAT);

        $projectDirSetting = (string)$this->getSetting(static::PROJECT_DIR_PATH)->getValues();
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
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
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
