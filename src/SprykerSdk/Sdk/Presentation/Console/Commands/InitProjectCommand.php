<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Yaml\Yaml;

class InitProjectCommand extends AbstractInitCommand
{
    /**
     * @var string
     */
    protected const NAME = 'init:project';

    /**
     */
    public function __construct(
        QuestionHelper $questionHelper,
        SettingRepositoryInterface $settingRepository,
        protected Yaml $yamlParser,
        protected string $projectSettingFileName,
    ) {
        parent::__construct(static::NAME, $questionHelper, $settingRepository);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $settingEntities = $this->settingRepository->findProjectSettings();
        $settingEntities = $this->initializeSettingValues($settingEntities, $input, $output);
        $this->writeProjectSettings($settingEntities, $input, $output);

        return static::SUCCESS;
    }


    /**
     * @todo that should be part of a different command
     * @param array $settingEntities
     */
    protected function writeProjectSettings(array $settingEntities, InputInterface $input, OutputInterface $output): void
    {
        $projectSettingPath = getcwd() . '/' . $this->projectSettingFileName;

        if (file_exists($projectSettingPath)) {
            if (!$this->questionHelper->ask(
                $input,
                $output,
                new ConfirmationQuestion('.ssdk file already exists, should it be overwritten?', false)
            )) {
                return;
            }
        }

        $projectSettings = array_filter($settingEntities, function (Setting $settingEntity) {
            return $settingEntity->isProject;
        });

        $projectValues = [];

        foreach ($projectSettings as $projectSetting) {
            $projectValues[$projectSetting->path] = $projectSetting->values;
        }

        file_put_contents($projectSettingPath, $this->yamlParser->dump($projectValues));
    }
}