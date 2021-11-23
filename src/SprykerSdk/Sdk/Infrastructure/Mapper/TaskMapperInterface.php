<?php

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;

interface TaskMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function mapToInfrastructureEntity(TaskInterface $task): Task;
}
