<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Dto;

class TaskYamlCriteriaDto
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var array
     */
    protected array $taskData;

    /**
     * @var array
     */
    protected array $taskListData;

    /**
     * @param string $type
     * @param array $taskData
     * @param array $taskListData
     */
    public function __construct(string $type, array $taskData, array $taskListData)
    {
        $this->type = $type;
        $this->taskData = $taskData;
        $this->taskListData = $taskListData;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getTaskData(): array
    {
        return $this->taskData;
    }

    /**
     * @return array
     */
    public function getTaskListData(): array
    {
        return $this->taskListData;
    }
}
