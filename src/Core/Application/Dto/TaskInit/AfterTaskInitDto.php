<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\TaskInit;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

class AfterTaskInitDto
{
    /**
     * @var \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected TaskInterface $task;

    /**
     * @var string
     */
    protected string $callSource = '';

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param string $callSource
     */
    public function __construct(TaskInterface $task, string $callSource)
    {
        $this->task = $task;
        $this->callSource = $callSource;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function getTask(): TaskInterface
    {
        return $this->task;
    }

    /**
     * @return string
     */
    public function getCallSource(): string
    {
        return $this->callSource;
    }
}
