<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use DateTimeInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

interface WorkflowTransitionInterface
{
    /**
     * @var string
     */
    public const WORKFLOW_TRANSITION_STARTED = 'transition_started';

    /**
     * @var string
     */
    public const WORKFLOW_TRANSITION_FINISHED = 'transition_finished';

    /**
     * @var string
     */
    public const NESTED_WORKFLOW_STARTED = 'nested_workflow_entered';

    /**
     * @var string
     */
    public const NESTED_WORKFLOW_FINISHED = 'nested_workflow_finished';

    /**
     * @var string
     */
    public const WORKFLOW_TASK_FAILED = 'task_failed';

    /**
     * @var string
     */
    public const WORKFLOW_TASK_SUCCEEDED = 'task_succeeded';

    /**
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface
     */
    public function getWorkflow(): WorkflowInterface;

    /**
     * @return array
     */
    public function getStatus(): array;

    /**
     * @return string|null
     */
    public function getTransition(): ?string;

    /**
     * @return string
     */
    public function getState(): string;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return \DateTimeInterface
     */
    public function getTime(): DateTimeInterface;
}
