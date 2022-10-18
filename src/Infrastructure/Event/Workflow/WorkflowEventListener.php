<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Workflow;

use SprykerSdk\Sdk\Infrastructure\Exception\InvalidServiceException;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Workflow\Event\EnteredEvent;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\Event\LeaveEvent;

class WorkflowEventListener
{
    /**
     * @var string
     */
    protected const WORKFLOW_GUARD = 'guard';

    /**
     * @var string
     */
    protected const WORKFLOW_BEFORE = 'before';

    /**
     * @var string
     */
    protected const WORKFLOW_AFTER = 'after';

    /**
     * @var string
     */
    protected const OPTION_FORCE = 'force';

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface|null
     */
    protected ?InputInterface $input = null;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleEvent $event
     *
     * @return void
     */
    public function setup(ConsoleEvent $event): void
    {
        $this->input = $event->getInput();
    }

    /**
     * @param \Symfony\Component\Workflow\Event\Event $event
     *
     * @return void
     */
    public function handle(Event $event): void
    {
        switch (true) {
            case $event instanceof GuardEvent:
                $this->guard($event);

                break;
            case $event instanceof LeaveEvent:
                $this->event($event, static::WORKFLOW_BEFORE);

                break;
            case $event instanceof EnteredEvent:
                $this->event($event, static::WORKFLOW_AFTER);
        }
    }

    /**
     * @param \Symfony\Component\Workflow\Event\GuardEvent $event
     *
     * @return void
     */
    public function guard(GuardEvent $event): void
    {
        if ($this->isForced()) {
            return;
        }

        [$workflowMeta, $transitionMeta] = $this->getMetadata($event);

        $this->checkGuards($event, $this->getHandlersFromMetadata($workflowMeta, static::WORKFLOW_GUARD));
        $this->checkGuards($event, $this->getHandlersFromMetadata($transitionMeta, static::WORKFLOW_GUARD));
    }

    /**
     * @param \Symfony\Component\Workflow\Event\Event $event
     * @param string $type
     *
     * @return void
     */
    public function event(Event $event, string $type): void
    {
        [$workflowMeta, $transitionMeta] = $this->getMetadata($event);

        $innerEvent = new WorkflowEvent($event, $event->getContext()['context'] ?? null);

        $this->executeHandlers($innerEvent, $this->getHandlersFromMetadata($workflowMeta, $type));
        $this->executeHandlers($innerEvent, $this->getHandlersFromMetadata($transitionMeta, $type));
    }

    /**
     * @return bool
     */
    protected function isForced(): bool
    {
        return $this->input
            && $this->input->hasOption(static::OPTION_FORCE)
            && $this->input->getOption(static::OPTION_FORCE);
    }

    /**
     * @param \Symfony\Component\Workflow\Event\Event $event
     *
     * @return array
     */
    protected function getMetadata(Event $event): array
    {
        $metadataStore = $event->getWorkflow()->getMetadataStore();

        $transitionMetadata = [];
        if ($event->getTransition()) {
            $transitionMetadata = $metadataStore->getTransitionMetadata($event->getTransition());
        }

        return [$metadataStore->getWorkflowMetadata(), $transitionMetadata];
    }

    /**
     * @param array $metadata
     * @param string $type
     *
     * @return array
     */
    protected function getHandlersFromMetadata(array $metadata, string $type): array
    {
        $services = [];
        $serviceIds = (array)($metadata[$type] ?? []);

        foreach ($serviceIds as $serviceId) {
            $services[] = $this->container->get($serviceId);
        }

        return $services;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowEventInterface $event
     * @param array $handlers
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidServiceException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowEventInterface
     */
    protected function executeHandlers(WorkflowEventInterface $event, array $handlers): WorkflowEventInterface
    {
        foreach ($handlers as $handler) {
            if (!$handler instanceof WorkflowEventHandlerInterface) {
                throw new InvalidServiceException(sprintf(
                    'Service "%s" must implement "%s"',
                    get_class($handler),
                    WorkflowEventHandlerInterface::class,
                ));
            }

            $handler->handle($event);
        }

        return $event;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\GuardEvent $event
     * @param array $guards
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidServiceException
     *
     * @return \Symfony\Component\Workflow\Event\GuardEvent
     */
    protected function checkGuards(GuardEvent $event, array $guards): GuardEvent
    {
        foreach ($guards as $guard) {
            if (!$guard instanceof WorkflowGuardEventHandlerInterface) {
                throw new InvalidServiceException(sprintf(
                    'Service "%s" must implement "%s"',
                    get_class($guard),
                    WorkflowEventHandlerInterface::class,
                ));
            }

            $guard->check($event);
        }

        return $event;
    }
}
