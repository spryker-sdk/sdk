<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Workflow;

use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use Symfony\Component\Workflow\Event\GuardEvent;

class WorkflowStartedTransitionListener
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     */
    public function __construct(ProjectWorkflow $projectWorkflow)
    {
        $this->projectWorkflow = $projectWorkflow;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\GuardEvent $event
     *
     * @return void
     */
    public function guard(GuardEvent $event): void
    {
        /** @var \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow */
        $workflow = $event->getSubject();
        $runningTransition = $this->projectWorkflow->getRunningTransition($workflow);

        if (!$runningTransition) {
            return;
        }

        if ($event->getTransition()->getName() !== $runningTransition->getTransition()) {
            $event->setBlocked(true, sprintf(
                'Another transition (%s) is already running',
                $runningTransition->getTransition(),
            ));
        }
    }
}
