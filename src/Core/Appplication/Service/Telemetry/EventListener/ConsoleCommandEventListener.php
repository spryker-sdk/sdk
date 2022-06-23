<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Telemetry\EventListener;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\Telemetry\TelemetryEventsSynchronizer;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\CommandFailedExecutionPayload;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\CommandSuccessfulExecutionPayload;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Throwable;

class ConsoleCommandEventListener
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface
     */
    protected TelemetryEventRepositoryInterface $telemetryEventRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Telemetry\TelemetryEventsSynchronizer
     */
    private TelemetryEventsSynchronizer $telemetryEventsSynchronizer;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TelemetryEventRepositoryInterface $telemetryEventRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\Telemetry\TelemetryEventsSynchronizer $telemetryEventsSynchronizer
     */
    public function __construct(
        TelemetryEventRepositoryInterface $telemetryEventRepository,
        TelemetryEventsSynchronizer $telemetryEventsSynchronizer
    ) {
        $this->telemetryEventRepository = $telemetryEventRepository;
        $this->telemetryEventsSynchronizer = $telemetryEventsSynchronizer;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $this->addSuccessfulCommandEvent($event);

        $this->synchronizeEvents($event);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleErrorEvent $event
     *
     * @return void
     */
    public function onConsoleError(ConsoleErrorEvent $event): void
    {
        $this->addFailedCommandEvent($event);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    protected function addSuccessfulCommandEvent(ConsoleTerminateEvent $event): void
    {
        $telemetryEvent = new TelemetryEvent(new CommandSuccessfulExecutionPayload(
            (string)($event->getCommand() !== null ? $event->getCommand()->getName() : ''),
            $event->getInput()->getArguments(),
            $event->getInput()->getOptions(),
        ));

        $this->telemetryEventRepository->save($telemetryEvent);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleErrorEvent $event
     *
     * @return void
     */
    protected function addFailedCommandEvent(ConsoleErrorEvent $event): void
    {
        $telemetryEvent = new TelemetryEvent(new CommandFailedExecutionPayload(
            (string)($event->getCommand() !== null ? $event->getCommand()->getName() : ''),
            $event->getInput()->getArguments(),
            $event->getInput()->getOptions(),
            $event->getError()->getMessage(),
            $event->getExitCode(),
        ));

        $this->telemetryEventRepository->save($telemetryEvent);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    protected function synchronizeEvents(ConsoleTerminateEvent $event): void
    {
        $event->getOutput()->writeln('<info>Telemetry events synchronization...</info>');

        try {
            $this->telemetryEventsSynchronizer->synchronize();
        } catch (Throwable $e) {
            $event->getOutput()->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        $event->getOutput()->writeln('<info>Telemetry events synchronization...</info>');
    }
}
