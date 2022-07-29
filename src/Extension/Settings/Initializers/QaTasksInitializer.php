<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Settings\Initializers;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Extension\Dependency\Setting\SettingChoicesProviderInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class QaTasksInitializer implements SettingChoicesProviderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return array<string>
     */
    public function getChoices(SettingInterface $setting): array
    {
        $tasks = $this->taskRepository->findAllIndexedCollection();
        $validationTasks = [];

        foreach (array_keys($tasks) as $task) {
            if (strpos($task, 'validation') !== false) {
                $validationTasks[] = $task;
            }
        }

        return $validationTasks;
    }
}
