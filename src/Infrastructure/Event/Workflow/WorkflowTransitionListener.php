<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Workflow;

use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use Symfony\Component\Workflow\Event\TransitionEvent;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\TransitionBlocker;
use Symfony\Component\Workflow\TransitionBlockerList;

class WorkflowTransitionListener
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor
     */
    protected TaskExecutor $taskExecutor;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     */
    public function __construct(TaskExecutor $taskExecutor)
    {
        $this->taskExecutor = $taskExecutor;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     *
     * @throws \Symfony\Component\Workflow\Exception\NotEnabledTransitionException
     *
     * @return void
     */
    public function execute(TransitionEvent $event): void
    {
        $meta = $event->getWorkflow()->getMetadataStore()->getTransitionMetadata($event->getTransition());
        $task = $meta['task'] ?? null;

        if (!$task) {
            return;
        }

        /** @var \SprykerSdk\SdkContracts\Entity\ContextInterface $context */
        $context = $event->getContext()['context'] ?? null;

        if (!$context instanceof ContextInterface) {
            throw $this->blockTransition(
                $event,
                'Context must be provided for transition associated with the task',
            );
        }

        $context = $this->taskExecutor->execute($task, $context);

        if ($context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
            throw $this->blockTransition(
                $event,
                'You cannot move to the next place in the workflow because your command failed',
            );
        }
    }

    /**
     * @param \Symfony\Component\Workflow\Event\TransitionEvent $event
     * @param string $message
     *
     * @return \Symfony\Component\Workflow\Exception\NotEnabledTransitionException
     */
    protected function blockTransition(TransitionEvent $event, string $message): NotEnabledTransitionException
    {
        return new NotEnabledTransitionException(
            $event->getSubject(),
            $event->getTransition() ? $event->getTransition()->getName() : '',
            $event->getWorkflow(),
            new TransitionBlockerList([
                TransitionBlocker::createUnknown($message),
            ]),
            $event->getContext(),
        );
    }
}
