<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * @todo :: This interface breaks SRP and must be refactored in the future.
 *      Also, Manager, Handler and the other general class naming forbidden in Spryker.
 */
interface TaskManagerInterface
{
    /**
     * @deprecated Will be removed in the future major release. Use \SprykerSdk\Sdk\Core\Application\Creator\TaskCreatorInterface::createTasks() instead.
     *
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto
     */
    public function initialize(InitializeCriteriaDto $criteriaDto): InitializeResultDto;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function remove(TaskInterface $task): void;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $folderTask
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $databaseTask
     *
     * @return void
     */
    public function update(TaskInterface $folderTask, TaskInterface $databaseTask): void;
}
