<?php

namespace Sdk\Setting;

use Sdk\Exception\SettingNotFoundException;
use Sdk\Style\StyleInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class Setting implements SettingInterface
{
    /**
     * @var string[]
     */
    protected array $settingDefinitionPaths;

    /**
     * @var string
     */
    protected string $settingsFilePath;

    /**
     * @param string[] $settingDefinitionPaths
     * @param string $settingsFilePath
     */
    public function __construct(array $settingDefinitionPaths, string $settingsFilePath)
    {
        $this->settingDefinitionPaths = $settingDefinitionPaths;
        $this->settingsFilePath = $settingsFilePath;
    }

    /**
     * @param string $name
     *
     * @throws \Sdk\Exception\SettingNotFoundException
     * @return mixed
     */
    public function getSetting(string $name)
    {
        if (!file_exists($this->settingsFilePath)) {
            throw new SettingNotFoundException('SDK needs to be initialized by calling spryker-sdk init');
        }
        $settings = $this->getExistingSettings();

        if (!isset($settings[$name])) {
            throw new SettingNotFoundException(sprintf('Setting name `%s` is missing.SDK needs to be initialized by calling spryker-sdk init', $name));
        }

        return $settings[$name];
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequiredSettings(): array
    {
        $settings = [];
        $settingFiles = $this->readSettingFiles();
        foreach ($settingFiles as $settingFile) {
            $fileSettings = Yaml::parse($settingFile->getContents());
            foreach ($fileSettings as $setting) {
                if (!isset($settings[$setting['name']])) {
                    $setting['mode'] = empty($setting['multiline']) ? InputOption::VALUE_REQUIRED : InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY;
                    $settings[$setting['name']] = $setting;
                }
            }
        }

        return $settings;
    }

    /**
     * @param array $settings
     * @param StyleInterface $style
     *
     * @return void
     */
    public function setSettings(array $settings, StyleInterface $style): void
    {
        $settings = \array_filter($settings);

        if (!file_exists($this->settingsFilePath)) {
            file_put_contents($this->settingsFilePath, '');
        }
        $existingSettings = $this->getExistingSettings();
        $requiredSettings = $this->getRequiredSettings();

        foreach ($requiredSettings as $requiredSettingName => $requiredSetting) {
            $value = isset($settings[$requiredSettingName]) ? $settings[$requiredSettingName] : null;

            if (!isset($existingSettings[$requiredSettingName]) && $value === null) {
                $question = $this->createSettingQuestion(
                    $requiredSetting['type'],
                    sprintf('Enter %s for <fg=yellow>%s</> setting (%s)', $requiredSetting['type'], $requiredSetting['name'], $requiredSetting['description']),
                    !empty($requiredSetting['multiline']),
                    $requiredSetting['defaultValues']
                );

                $value = $style->askQuestion($question);
            }
            if (!$value && $value !== false) {
                continue;
            }
            switch ($requiredSetting['strategy']) {
                case 'merge':
                    $existingSettings[$requiredSettingName] = (
                        !isset($existingSettings[$requiredSettingName]) ||
                        !is_array($existingSettings[$requiredSettingName])
                    ) ? (array)$value : \array_merge($existingSettings[$requiredSettingName], $value);

                    $existingSettings[$requiredSettingName] = array_unique($existingSettings[$requiredSettingName]);

                    break;
                default:
                    $existingSettings[$requiredSettingName] = $value;
            }
        }

        $this->saveSettings($existingSettings);
    }

    /**
     * @param string $type
     * @param string $message
     * @param bool $multiline
     * @param string $defaultValue
     *
     * @return mixed
     */
    protected function createSettingQuestion(string $type, string $message, bool $multiline = false, $defaultValue = null): Question
    {
        switch ($type) {
            case 'bool':
                $question = new ConfirmationQuestion(
                    $message,
                    (int)$defaultValue
                );

                break;
            default:
                $question = new Question(
                    $message,
                    $defaultValue
                );
                $question->setNormalizer(function ($value) {
                    return $value ?: '';
                });
        }

        if ($defaultValue === null) {
            $question->setValidator(function ($value) {
                if (!$value) {
                    throw new InvalidArgumentException('Value is invalid');
                }

                return $value;
            });
        }

        if ($multiline) {
            $question->setMultiline(true);
        }

        return $question;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    protected function readSettingFiles(): Finder
    {
        $finder = new Finder();
        $finder->in($this->settingDefinitionPaths)->files();

        return $finder;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getExistingSettings(): array
    {
        $settings = [];
        $existingSettings = Yaml::parseFile($this->settingsFilePath);
        if ($existingSettings && is_array($existingSettings)) {
            foreach ($existingSettings as $name => $existingSetting) {
                $settings[$name] = $existingSetting;
            }
        }
        return $settings;
    }

    /**
     * @param array $setting
     *
     * @return void
     */
    protected function saveSettings(array $setting): void
    {
        $yamlSettings = Yaml::dump($setting);

        file_put_contents($this->settingsFilePath, $yamlSettings);
    }
}
