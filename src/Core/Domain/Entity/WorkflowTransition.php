<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use DateTime;
use DateTimeInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface;

class WorkflowTransition implements WorkflowTransitionInterface
{
    /**
     * @var \SprykerSdk\SdkContracts\Entity\WorkflowInterface
     */
    protected WorkflowInterface $workflow;

    /**
     * @var array
     */
    protected array $status = [];

    /**
     * @var string|null
     */
    protected ?string $transition;

    /**
     * @var string
     */
    protected string $state;

    /**
     * @var array
     */
    protected array $data;

    /**
     * @var \DateTimeInterface
     */
    protected DateTimeInterface $time;

    /**
     * @param array $status
     * @param string|null $transition
     * @param string $state
     * @param array $data
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface|null $workflow
     */
    public function __construct(
        array $status,
        ?string $transition,
        string $state,
        array $data = [],
        ?WorkflowInterface $workflow = null
    ) {
        $this->status = $status;
        $this->transition = $transition;
        $this->state = $state;
        $this->data = $data;
        $this->time = new DateTime();

        if ($workflow) {
            $this->workflow = $workflow;
        }
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface
     */
    public function getWorkflow(): WorkflowInterface
    {
        return $this->workflow;
    }

    /**
     * @return array
     */
    public function getStatus(): array
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getTransition(): ?string
    {
        return $this->transition;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getTime(): DateTimeInterface
    {
        return $this->time;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
