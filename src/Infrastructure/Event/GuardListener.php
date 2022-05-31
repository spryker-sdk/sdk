<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use SprykerSdk\Sdk\Extension\Dependency\Events\GuardEventInterface;
use SprykerSdk\Sdk\Extension\Dependency\Events\GuardHandlerInterface;
use SprykerSdk\Sdk\Extension\Exception\InvalidServiceException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Workflow\Event\GuardEvent as WorkflowGuardEvent;

class GuardListener
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Symfony\Component\Workflow\Event\GuardEvent $event
     *
     * @return void
     */
    public function guardWorkflow(WorkflowGuardEvent $event): void
    {
        $metadataStore = $event->getWorkflow()->getMetadataStore();
        $workflowMetadata = $metadataStore->getWorkflowMetadata();
        $transitionMetadata = $metadataStore->getTransitionMetadata($event->getTransition());

        $innerEvent = new GuardEvent($event, $event->getContext()['context'] ?? null);

        $this->executeGuards($innerEvent, $this->getGuardsFromMetadata($workflowMetadata));
        $this->executeGuards($innerEvent, $this->getGuardsFromMetadata($transitionMetadata));

        if ($innerEvent->isBlocked()) {
            $event->setBlocked(true, $innerEvent->getBlockedReason());
        }
    }

    /**
     * @param array $metadata
     *
     * @throws \SprykerSdk\Sdk\Extension\Exception\InvalidServiceException
     *
     * @return array<\SprykerSdk\Sdk\Extension\Dependency\Events\GuardHandlerInterface>
     */
    protected function getGuardsFromMetadata(array $metadata): array
    {
        $services = [];
        $guards = (array)($metadata['guard'] ?? []);

        foreach ($guards as $guard) {
            $service = $this->container->get($guard);

            if (!$service instanceof GuardHandlerInterface) {
                throw new InvalidServiceException(sprintf(
                    'Service "%s" must implement "%s"',
                    $guard,
                    GuardHandlerInterface::class,
                ));
            }

            $services[] = $service;
        }

        return $services;
    }

    /**
     * @param \SprykerSdk\Sdk\Extension\Dependency\Events\GuardEventInterface $event
     * @param array<\SprykerSdk\Sdk\Extension\Dependency\Events\GuardHandlerInterface> $guards
     *
     * @return \SprykerSdk\Sdk\Extension\Dependency\Events\GuardEventInterface
     */
    protected function executeGuards(GuardEventInterface $event, array $guards): GuardEventInterface
    {
        foreach ($guards as $guard) {
            $guard->check($event);
        }

        return $event;
    }
}
