<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

abstract class AbstractInitCommand extends Command
{
    public function __construct(
        string $name,
        protected QuestionHelper $questionHelper,
        protected SettingRepositoryInterface $settingRepository
    ) {
        parent::__construct($name);
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

            if ($settingEntity->values === null) {
                $values = $this->questionHelper->ask(
                    $input,
                    $output,
                    new Question($settingEntity->initializationDescription ?? 'Initial value for ' . $settingEntity->path, is_scalar($settingEntity->values) ?? json_encode($settingEntity->values))
                );
                $values = is_scalar($values) ?? json_decode($values);
                $previousSettingValues = $settingEntity->values;
                $settingEntity->values = $values;

                //@todo move to InitCommand & only pass core values
                if ($settingEntity->isProject === false && $values !== $previousSettingValues) {
                    $this->settingRepository->save($settingEntity);
                }
            }
        }

        return $settingEntities;
    }
}