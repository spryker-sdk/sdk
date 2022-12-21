<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command\TaskLoader;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskSetTaskRelationRepositoryInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskHelpMessageDecorator implements TaskHelpMessageDecoratorInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskSetTaskRelationRepositoryInterface
     */
    protected TaskSetTaskRelationRepositoryInterface $taskSetTaskRelationRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskSetTaskRelationRepositoryInterface $taskSetTaskRelationRepository
     */
    public function __construct(TaskSetTaskRelationRepositoryInterface $taskSetTaskRelationRepository)
    {
        $this->taskSetTaskRelationRepository = $taskSetTaskRelationRepository;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return string
     */
    public function decorateHelpMessage(TaskInterface $task): string
    {
        $helpMessages = $task->getHelp() !== null ? [(string)$task->getHelp()] : [];

        $taskSetTaskRelations = $this->taskSetTaskRelationRepository->getByTaskSetId($task->getId());

        if (count($taskSetTaskRelations) > 0) {
            $helpMessages[] = '<comment>Task set sub-tasks:</comment>';
        }

        foreach ($taskSetTaskRelations as $taskSetTaskRelation) {
            $helpMessages[] = sprintf(
                "<info> - %s</info>\t%s",
                $taskSetTaskRelation->getSubTask()->getId(),
                $taskSetTaskRelation->getSubTask()->getHelp(),
            );
        }

        return implode(PHP_EOL, $helpMessages);
    }
}
