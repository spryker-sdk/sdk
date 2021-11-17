<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Service\ProjectSettingManager;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class InitProjectCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'init:project';

    /**
     */
    public function __construct(
        protected QuestionHelper $questionHelper,
        protected ProjectSettingManager $projectSettingManager,
        protected SettingRepositoryInterface $settingRepository,
        protected string $projectSettingFileName
    ) {
        parent::__construct(static::NAME);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $projectSettingPath = getcwd() . '/' . $this->projectSettingFileName;

        if (file_exists($projectSettingPath)) {
            if (!$this->questionHelper->ask(
                $input,
                $output,
                new ConfirmationQuestion('.ssdk file already exists, should it be overwritten? [n]', false)
            )) {
                return static::SUCCESS;
            }
        }

        $settingEntities = $this->settingRepository->findProjectSettings();
        $settingEntities = $this->initializeSettingValues($settingEntities, $input, $output);
        $this->writeProjectSettings($settingEntities);

        return static::SUCCESS;
    }

    /**
     * @param array $settingEntities
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting>
     */
    protected function initializeSettingValues(array $settingEntities, InputInterface $input, OutputInterface $output): array
    {
        foreach ($settingEntities as $settingEntity) {
            if ($settingEntity->hasInitialization === false) {
                continue;
            }

            $question = $this->buildQuestion($settingEntity);

            $values = $this->questionHelper->ask($input, $output, $question);

            $values = match ($settingEntity->type) {
                'bool' => (bool)$values,
                'array' => (array)$values,
                default => (string)$values,
            };

            if ($settingEntity->strategy === 'merge') {
                $values = array_merge($settingEntity->values, $values);
            }

            $settingEntity->values = $values;
        }

        return $settingEntities;
    }


    /**
     * @param array $settingEntities
     */
    protected function writeProjectSettings(array $projectSettings): void
    {
        $projectValues = [];

        foreach ($projectSettings as $projectSetting) {
            $projectValues[$projectSetting->path] = $projectSetting->values;
        }

        $this->projectSettingManager->setSettings($projectValues);
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Setting $settingEntity
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    protected function buildQuestion(Setting $settingEntity): Question
    {
        $questionDescription = $settingEntity->initializationDescription;

        if (empty($questionDescription)) {
           $questionDescription = 'Initial value for ' . $settingEntity->path;
        }

        $defaultValue = match ($settingEntity->type) {
            'bool' => $settingEntity->values ? 'y' : 'n',
            'array' => json_encode($settingEntity->values),
            default => (string)$settingEntity->values,
        };

        $questionDescription .= '[' . $defaultValue . ']';


        if ($settingEntity->type === 'bool') {
            return new ConfirmationQuestion($questionDescription, (bool)$defaultValue);
        }

        return new Question($questionDescription, $defaultValue);
    }
}